<?php

namespace App\Services;

use App\Models\Entrega;
use App\Models\Garantia;
use App\Models\MovimientoCaja;
use App\Models\OrdenServicio;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * PagoService
 * -----------
 * Gestiona el registro de pagos, el cálculo del anticipo/saldo
 * y la generación del comprobante de entrega.
 *
 * Reglas de negocio cubiertas:
 *  - El cliente paga un anticipo del 50 % antes de iniciar la reparación.
 *  - Al finalizar, el saldo pendiente = importe final − pagos realizados.
 *  - Cada pago registra importe, fecha, método, orden y usuario responsable.
 *  - Recepción verifica y registra la entrega del equipo.
 *  - La garantía se vincula a la orden original con periodo, condiciones y conceptos.
 */
class PagoService
{
    public function __construct(
        private readonly BitacoraService     $bitacora,
        private readonly NotificacionService $notificaciones,
    ) {}

    // ── Anticipo ─────────────────────────────────────────────────────────────

    /**
     * Valida que el anticipo requerido haya sido pagado.
     * Lanza ValidationException si no se ha cubierto el porcentaje mínimo.
     */
    public function validarAnticipo(OrdenServicio $orden): void
    {
        $cotizacion = $orden->cotizacion;

        if (! $cotizacion || ! $cotizacion->requiere_anticipo) {
            return; // Sin cotización o sin requisito de anticipo
        }

        $versionActual = $cotizacion->versionActual;
        if (! $versionActual) return;

        $totalCotizado = (float) $versionActual->total;
        $porcentaje    = (float) ($cotizacion->porcentaje_anticipo ?? 50);
        $anticipoMin   = round($totalCotizado * $porcentaje / 100, 2);

        $pagado = Pago::where('id_orden', $orden->id_orden)->where('estado', 'Registrado')->sum('monto');

        if ($pagado < $anticipoMin) {
            throw ValidationException::withMessages([
                'anticipo' => "Se requiere un anticipo de \${$anticipoMin}. Solo se han registrado \${$pagado}.",
            ]);
        }
    }

    // ── Registro de pago ─────────────────────────────────────────────────────

    /**
     * Registra un pago y su movimiento de caja correspondiente.
     */
    public function registrarPago(
        OrdenServicio $orden,
        int $idCliente,
        int $idUsuario,
        float $monto,
        string $metodoPago,
        ?string $referencia = null,
        ?string $observaciones = null,
    ): Pago {
        return DB::transaction(function () use ($orden, $idCliente, $idUsuario, $monto, $metodoPago, $referencia, $observaciones) {
            $pago = Pago::create([
                'id_orden'      => $orden->id_orden,
                'id_cliente'    => $idCliente,
                'id_usuario'    => $idUsuario,
                'monto'         => $monto,
                'metodo_pago'   => $metodoPago,
                'referencia'    => $referencia,
                'fecha_pago'    => now(),
                'estado'        => 'Registrado',
                'observaciones' => $observaciones,
            ]);

            MovimientoCaja::create([
                'id_pago'          => $pago->id_pago,
                'id_usuario'       => $idUsuario,
                'tipo_movimiento'  => 'Ingreso',
                'monto'            => $monto,
                'concepto'         => "Pago orden {$orden->folio} – {$metodoPago}",
                'fecha_movimiento' => now(),
            ]);

            $this->bitacora->pagoRegistrado($pago->id_pago, (string) $monto, $metodoPago);
            $this->notificaciones->pagoRegistrado($idCliente, $orden->folio ?? "#{$orden->id_orden}", "\${$monto}");

            return $pago;
        });
    }

    /**
     * Calcula el saldo pendiente de la orden (importe total − pagos registrados).
     */
    public function calcularSaldoPendiente(OrdenServicio $orden): float
    {
        $total  = (float) ($orden->cotizacion?->versionActual?->total ?? 0);
        $pagado = (float) Pago::where('id_orden', $orden->id_orden)->where('estado', 'Registrado')->sum('monto');

        return max(0.0, round($total - $pagado, 2));
    }

    // ── Entrega y garantía ───────────────────────────────────────────────────

    /**
     * Registra la entrega del equipo al cliente.
     * Verifica que el saldo esté liquidado y que las pruebas finales se hayan registrado.
     */
    public function registrarEntrega(
        OrdenServicio $orden,
        int $idResponsable,
        string $nombreReceptor,
        ?string $identificacionReceptor = null,
        ?string $folioComprobante = null,
        ?string $observaciones = null,
    ): Entrega {
        // Saldo pendiente
        $saldo = $this->calcularSaldoPendiente($orden);
        if ($saldo > 0) {
            throw ValidationException::withMessages([
                'saldo' => "Hay un saldo pendiente de \${$saldo}. El equipo no puede entregarse hasta liquidar.",
            ]);
        }

        // Prueba final registrada
        $reparacion = $orden->reparacion;
        if (! $reparacion || ! $reparacion->pruebas()->exists()) {
            throw ValidationException::withMessages([
                'prueba' => 'Se requiere registrar las pruebas finales antes de la entrega.',
            ]);
        }

        return DB::transaction(function () use ($orden, $idResponsable, $nombreReceptor, $identificacionReceptor, $folioComprobante, $observaciones) {
            $entrega = Entrega::create([
                'id_orden'               => $orden->id_orden,
                'id_responsable_entrega' => $idResponsable,
                'nombre_receptor'        => $nombreReceptor,
                'identificacion_receptor' => $identificacionReceptor,
                'fecha_entrega'          => now(),
                'folio_comprobante'      => $folioComprobante ?? $this->generarFolioComprobante($orden),
                'observaciones'          => $observaciones,
            ]);

            $orden->update(['estado_orden' => 'Entregada']);
            $orden->historialEstados()->create([
                'id_usuario'     => $idResponsable,
                'estado_anterior' => $orden->estado_orden,
                'estado_nuevo'   => 'Entregada',
                'comentario'     => "Entregado a {$nombreReceptor}",
                'fecha_cambio'   => now(),
            ]);

            $this->bitacora->cambioEstadoOrden($orden->id_orden, $orden->estado_orden, 'Entregada', $idResponsable);

            return $entrega;
        });
    }

    /**
     * Emite la garantía al finalizar la entrega.
     */
    public function emitirGarantia(
        OrdenServicio $orden,
        int $idUsuario,
        \DateTime $fechaFin,
        string $condiciones,
        ?string $conceptosCubiertos = null,
        ?string $duracion = null,
    ): Garantia {
        $idCliente = $orden->equipo?->id_cliente;

        if (! $idCliente) {
            throw ValidationException::withMessages(['cliente' => 'No se puede emitir garantía sin cliente asociado.']);
        }

        return DB::transaction(function () use ($orden, $idUsuario, $fechaFin, $condiciones, $conceptosCubiertos, $duracion, $idCliente) {
            $garantia = Garantia::create([
                'id_orden'           => $orden->id_orden,
                'id_cliente'         => $idCliente,
                'id_usuario'         => $idUsuario,
                'fecha_inicio'       => today(),
                'fecha_fin'          => $fechaFin,
                'duracion'           => $duracion,
                'condiciones'        => $condiciones,
                'conceptos_cubiertos' => $conceptosCubiertos,
                'estado'             => 'Vigente',
            ]);

            $this->bitacora->registrar('garantia_emitida', 'Garantia', $garantia->id_garantia,
                "Válida hasta {$fechaFin->format('Y-m-d')}", $idUsuario);

            return $garantia;
        });
    }

    // ── Helper ───────────────────────────────────────────────────────────────

    private function generarFolioComprobante(OrdenServicio $orden): string
    {
        return 'REC-' . ($orden->folio ?? $orden->id_orden) . '-' . now()->format('YmdHis');
    }
}
