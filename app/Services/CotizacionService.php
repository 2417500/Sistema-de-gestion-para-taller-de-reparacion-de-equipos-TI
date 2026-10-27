<?php

namespace App\Services;

use App\Models\Aprobacion;
use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\OrdenServicio;
use App\Models\ReglaDescuento;
use App\Models\VersionCotizacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * CotizacionService
 * -----------------
 * Gestiona el ciclo completo de cotizaciones.
 *
 * Reglas de negocio cubiertas:
 *  - Toda cotización tiene fecha de emisión, versión, vigencia, detalles y totales.
 *  - Modificar precio/refacciones/mano de obra genera nueva versión; las anteriores se conservan.
 *  - El cliente puede aceptar o rechazar; ambas decisiones se registran con fecha y motivo.
 *  - El administrador puede aprobar/rechazar; el rechazo requiere motivo.
 *  - Ninguna reparación puede iniciar sin cotización vigente aceptada.
 *  - Si la cotización vence sin aceptación, la reparación no puede comenzar.
 *  - El descuento se evalúa y registra; no se acumula automáticamente.
 *  - Se notifica al cliente cuando se genera la cotización.
 */
class CotizacionService
{
    public function __construct(
        private readonly BitacoraService    $bitacora,
        private readonly NotificacionService $notificaciones,
        private readonly DescuentoService   $descuentos,
    ) {}

    // ── Creación / nueva versión ─────────────────────────────────────────────

    /**
     * Genera la primera versión de una cotización para la orden indicada.
     *
     * @param  OrdenServicio  $orden
     * @param  array          $detalles    [['tipo_concepto', 'descripcion', 'cantidad', 'precio_unitario'], ...]
     * @param  int            $idUsuario   Técnico/recepcionista que la emite
     * @param  \DateTime|null $vigencia    Fecha de vencimiento (null = +7 días)
     * @param  string|null    $condiciones
     */
    public function crearCotizacion(
        OrdenServicio $orden,
        array $detalles,
        int $idUsuario,
        ?\DateTime $vigencia = null,
        ?string $condiciones = null,
    ): VersionCotizacion {
        return DB::transaction(function () use ($orden, $detalles, $idUsuario, $vigencia, $condiciones) {
            // Una sola cotización por orden
            $cotizacion = $orden->cotizacion ?? Cotizacion::create([
                'id_orden'          => $orden->id_orden,
                'estado'            => 'Pendiente',
                'requiere_anticipo' => true,
                'porcentaje_anticipo' => 50,
            ]);

            $version = $this->crearVersion($cotizacion, $detalles, $idUsuario, $vigencia, $condiciones);

            // Notificar al cliente
            $idCliente = $orden->equipo?->id_cliente;
            if ($idCliente) {
                $this->notificaciones->cotizacionGenerada($idCliente, $orden->folio ?? "#{$orden->id_orden}");
            }

            $this->bitacora->aprobacionCotizacion($version->id_version, 'generada');

            return $version;
        });
    }

    /**
     * Genera una nueva versión cuando se modifica una cotización existente.
     */
    public function nuevaVersion(
        Cotizacion $cotizacion,
        array $detalles,
        int $idUsuario,
        ?\DateTime $vigencia = null,
        ?string $condiciones = null,
    ): VersionCotizacion {
        return DB::transaction(function () use ($cotizacion, $detalles, $idUsuario, $vigencia, $condiciones) {
            $version = $this->crearVersion($cotizacion, $detalles, $idUsuario, $vigencia, $condiciones);

            // Reactivar cotización si estaba vencida/rechazada
            if (in_array($cotizacion->estado, ['Rechazada', 'Vencida'], true)) {
                $cotizacion->update(['estado' => 'Pendiente']);
            }

            $this->bitacora->aprobacionCotizacion($version->id_version, 'nueva_version');
            $this->notificaciones->autorizacionRequerida("Nueva versión de cotización para orden {$cotizacion->orden?->folio}. Requiere revisión del administrador.");

            return $version;
        });
    }

    // ── Decisión del cliente ─────────────────────────────────────────────────

    /**
     * El cliente acepta la versión vigente de la cotización.
     */
    public function aceptarPorCliente(VersionCotizacion $version, int $idCliente): Aprobacion
    {
        $this->validarVigencia($version);

        return DB::transaction(function () use ($version, $idCliente) {
            $aprobacion = Aprobacion::updateOrCreate(
                ['id_version' => $version->id_version, 'id_cliente' => $idCliente],
                ['estado' => 'Aprobada', 'fecha_respuesta' => now(), 'comentarios' => null, 'motivo_rechazo' => null],
            );

            $version->update(['fecha_aceptacion' => now()]);
            $version->cotizacion->update(['estado' => 'Aprobada']);

            $this->bitacora->aprobacionCotizacion($version->id_version, 'aprobada_cliente');

            return $aprobacion;
        });
    }

    /**
     * El cliente rechaza la versión indicando el motivo.
     */
    public function rechazarPorCliente(VersionCotizacion $version, int $idCliente, string $motivo): Aprobacion
    {
        if (blank($motivo)) {
            throw ValidationException::withMessages(['motivo' => 'El motivo de rechazo es obligatorio.']);
        }

        return DB::transaction(function () use ($version, $idCliente, $motivo) {
            $aprobacion = Aprobacion::updateOrCreate(
                ['id_version' => $version->id_version, 'id_cliente' => $idCliente],
                ['estado' => 'Rechazada', 'fecha_respuesta' => now(), 'motivo_rechazo' => $motivo],
            );

            $version->cotizacion->update(['estado' => 'Rechazada']);
            $this->bitacora->aprobacionCotizacion($version->id_version, 'rechazada_cliente', $motivo);
            $this->notificaciones->autorizacionRequerida("El cliente rechazó la cotización de la orden {$version->cotizacion->orden?->folio}. Motivo: {$motivo}");

            return $aprobacion;
        });
    }

    // ── Decisión administrativa ──────────────────────────────────────────────

    public function aprobarPorAdministrador(VersionCotizacion $version, int $idAdmin): void
    {
        DB::transaction(function () use ($version, $idAdmin) {
            $version->cotizacion->update(['estado' => 'AprobadaAdmin']);
            $this->bitacora->aprobacionCotizacion($version->id_version, 'aprobada_admin');
        });
    }

    public function rechazarPorAdministrador(VersionCotizacion $version, int $idAdmin, string $motivo): void
    {
        if (blank($motivo)) {
            throw ValidationException::withMessages(['motivo' => 'El motivo de rechazo administrativo es obligatorio.']);
        }

        DB::transaction(function () use ($version, $idAdmin, $motivo) {
            $version->cotizacion->update(['estado' => 'RechazadaAdmin']);
            $this->bitacora->aprobacionCotizacion($version->id_version, 'rechazada_admin', $motivo);
            $this->bitacora->rechazoSolicitud($version->id_version, $motivo, 'VersionCotizacion');
        });
    }

    // ── Validación para inicio de reparación ─────────────────────────────────

    /**
     * Verifica que existan todas las condiciones para iniciar la reparación:
     *  1. Cotización vigente aceptada por el cliente.
     *  2. Anticipo pagado (si aplica).
     * Lanza ValidationException si alguna condición no se cumple.
     */
    public function validarCondicionesParaReparacion(OrdenServicio $orden): void
    {
        $cotizacion = $orden->cotizacion;

        if (! $cotizacion) {
            throw ValidationException::withMessages(['cotizacion' => 'La orden no tiene cotización registrada.']);
        }

        $versionVigente = $cotizacion->versionActual;

        if (! $versionVigente) {
            throw ValidationException::withMessages(['cotizacion' => 'No existe una versión de cotización activa.']);
        }

        $this->validarVigencia($versionVigente);

        if ($cotizacion->estado !== 'Aprobada') {
            throw ValidationException::withMessages([
                'cotizacion' => 'La cotización no ha sido aceptada por el cliente. La reparación no puede iniciar.',
            ]);
        }
    }

    // ── Helpers privados ─────────────────────────────────────────────────────

    private function crearVersion(
        Cotizacion $cotizacion,
        array $detalles,
        int $idUsuario,
        ?\DateTime $vigencia,
        ?string $condiciones,
    ): VersionCotizacion {
        $numeroVersion = ($cotizacion->versiones()->max('numero_version') ?? 0) + 1;

        [$subtotal, $descuento, $porcentaje, $regla] = $this->descuentos->calcularParaCotizacion(
            $cotizacion->orden->equipo->id_cliente,
            $this->calcularSubtotal($detalles),
        );

        $version = VersionCotizacion::create([
            'id_cotizacion'                => $cotizacion->id_cotizacion,
            'id_regla_descuento'           => $regla?->id_regla_descuento,
            'id_usuario'                   => $idUsuario,
            'numero_version'               => $numeroVersion,
            'subtotal'                     => $subtotal,
            'descuento'                    => $descuento,
            'porcentaje_descuento_aplicado' => $porcentaje,
            'total'                        => $subtotal - $descuento,
            'condiciones'                  => $condiciones,
            'fecha_emision'                => now(),
            'fecha_vencimiento'            => $vigencia ?? now()->addDays(7),
        ]);

        foreach ($detalles as $det) {
            $importe = round(($det['cantidad'] ?? 1) * $det['precio_unitario'], 2);
            DetalleCotizacion::create([
                'id_version'     => $version->id_version,
                'id_refaccion'   => $det['id_refaccion'] ?? null,
                'tipo_concepto'  => $det['tipo_concepto'],
                'descripcion'    => $det['descripcion'],
                'cantidad'       => $det['cantidad'] ?? 1,
                'precio_unitario' => $det['precio_unitario'],
                'importe'        => $importe,
            ]);
        }

        return $version;
    }

    private function calcularSubtotal(array $detalles): float
    {
        return array_reduce($detalles, fn ($carry, $d) => $carry + (($d['cantidad'] ?? 1) * $d['precio_unitario']), 0.0);
    }

    private function validarVigencia(VersionCotizacion $version): void
    {
        if ($version->fecha_vencimiento && now()->gt($version->fecha_vencimiento)) {
            throw ValidationException::withMessages([
                'cotizacion' => 'La cotización ha vencido. Es necesario generar una nueva versión.',
            ]);
        }
    }
}
