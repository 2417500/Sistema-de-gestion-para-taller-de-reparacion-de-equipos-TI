<?php

namespace App\Services;

use App\Models\Equipo;
use App\Models\EvidenciaIngreso;
use App\Models\OrdenServicio;
use App\Models\SolicitudAutorizacion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * OrdenServicioService
 * --------------------
 * Gestiona la recepción de equipos y la creación de órdenes de servicio.
 *
 * Reglas de negocio cubiertas:
 *  - Cada equipo recibido genera una orden con folio único.
 *  - Si el cliente desea múltiples equipos, verifica autorización del administrador.
 *  - Recepción registra características, accesorios, estado físico y evidencias.
 *  - Reingreso por garantía: crea nueva orden relacionada con la original.
 *  - Una vez iniciada la reparación, la orden no puede cancelarse ordinariamente.
 *  - Conserva historial completo de cambios de estado.
 */
class OrdenServicioService
{
    public function __construct(
        private readonly BitacoraService    $bitacora,
        private readonly NotificacionService $notificaciones,
    ) {}

    // ── Creación de orden ────────────────────────────────────────────────────

    /**
     * Crea una nueva orden de servicio para un equipo.
     *
     * @param  array           $datosOrden   Campos de ordenes_servicio
     * @param  array           $datosEquipo  Campos de equipos (si el equipo es nuevo)
     * @param  UploadedFile[]  $evidencias   Imágenes/videos de ingreso
     * @param  int             $idEquipo     Si el equipo ya existe, omitir datosEquipo
     */
    public function crearOrden(
        array $datosOrden,
        int $idRecepcionista,
        int $idEquipo,
        array $evidencias = [],
    ): OrdenServicio {
        return DB::transaction(function () use ($datosOrden, $idRecepcionista, $idEquipo, $evidencias) {
            $equipo = Equipo::findOrFail($idEquipo);

            // Verificar si el cliente ya tiene órdenes activas y necesita autorización
            $this->verificarLimiteEquipos($equipo->id_cliente);

            $orden = OrdenServicio::create([
                'folio'                  => $this->generarFolio(),
                'id_equipo'              => $idEquipo,
                'id_recepcionista'       => $idRecepcionista,
                'fecha_recepcion'        => now(),
                'falla_reportada'        => $datosOrden['falla_reportada'],
                'accesorios_recibidos'   => $datosOrden['accesorios_recibidos'] ?? null,
                'estado_fisico_ingreso'  => $datosOrden['estado_fisico_ingreso'] ?? null,
                'estado_orden'           => 'Recibida',
                'observaciones'          => $datosOrden['observaciones'] ?? null,
                'es_reingreso'           => false,
            ]);

            $this->registrarEvidencias($orden, $evidencias, $idRecepcionista);
            $this->registrarHistorial($orden, null, 'Recibida', $idRecepcionista, 'Orden creada');
            $this->bitacora->cambioEstadoOrden($orden->id_orden, 'Nueva', 'Recibida', $idRecepcionista);

            return $orden;
        });
    }

    /**
     * Crea una orden de reingreso relacionada con la orden original (garantía).
     */
    public function crearReingreso(
        OrdenServicio $ordenOriginal,
        array $datosOrden,
        int $idRecepcionista,
        array $evidencias = [],
    ): OrdenServicio {
        // Verificar que la garantía siga vigente
        $garantia = $ordenOriginal->garantia;

        if (! $garantia || $garantia->estado !== 'Vigente' || today()->gt($garantia->fecha_fin)) {
            throw ValidationException::withMessages([
                'garantia' => 'La garantía de esta orden ya no está vigente.',
            ]);
        }

        return DB::transaction(function () use ($ordenOriginal, $datosOrden, $idRecepcionista, $evidencias) {
            $orden = OrdenServicio::create([
                'folio'                 => $this->generarFolio('RE'),
                'id_equipo'             => $ordenOriginal->id_equipo,
                'id_recepcionista'      => $idRecepcionista,
                'id_orden_origen'       => $ordenOriginal->id_orden,
                'es_reingreso'          => true,
                'fecha_recepcion'       => now(),
                'falla_reportada'       => $datosOrden['falla_reportada'],
                'accesorios_recibidos'  => $datosOrden['accesorios_recibidos'] ?? null,
                'estado_fisico_ingreso' => $datosOrden['estado_fisico_ingreso'] ?? null,
                'estado_orden'          => 'Reingreso',
                'observaciones'         => $datosOrden['observaciones'] ?? null,
            ]);

            $this->registrarEvidencias($orden, $evidencias, $idRecepcionista);
            $this->registrarHistorial($orden, null, 'Reingreso', $idRecepcionista, 'Reingreso por garantía');
            $this->bitacora->cambioEstadoOrden($orden->id_orden, 'Nueva', 'Reingreso', $idRecepcionista);

            return $orden;
        });
    }

    // ── Cambio de estado ─────────────────────────────────────────────────────

    /**
     * Actualiza el estado de la orden y registra el cambio en el historial.
     */
    public function cambiarEstado(
        OrdenServicio $orden,
        string $nuevoEstado,
        int $idUsuario,
        ?string $comentario = null,
    ): void {
        $estadosNoEditables = ['En Reparación', 'Entregada', 'Cancelada'];

        if (in_array($orden->estado_orden, ['Entregada', 'Cancelada'], true)) {
            throw ValidationException::withMessages([
                'estado' => "La orden en estado '{$orden->estado_orden}' no puede modificarse.",
            ]);
        }

        $estadoAnterior = $orden->estado_orden;

        DB::transaction(function () use ($orden, $nuevoEstado, $idUsuario, $comentario, $estadoAnterior) {
            $orden->update(['estado_orden' => $nuevoEstado]);
            $this->registrarHistorial($orden, $estadoAnterior, $nuevoEstado, $idUsuario, $comentario);
            $this->bitacora->cambioEstadoOrden($orden->id_orden, $estadoAnterior, $nuevoEstado, $idUsuario);

            $idCliente = $orden->equipo?->id_cliente;
            if ($idCliente) {
                $this->notificaciones->cambioEstadoOrden($idCliente, $orden->folio ?? "#{$orden->id_orden}", $nuevoEstado);
            }
        });
    }

    /**
     * Cancela una orden que aún NO ha iniciado reparación.
     * Si ya está en reparación, lanza excepción (debe usarse incidencia).
     */
    public function cancelarOrden(OrdenServicio $orden, int $idUsuario, string $motivo): void
    {
        if (in_array($orden->estado_orden, ['En Reparación'], true)) {
            throw ValidationException::withMessages([
                'estado' => 'Una orden en reparación no puede cancelarse por el procedimiento ordinario. Registre una incidencia.',
            ]);
        }

        if (in_array($orden->estado_orden, ['Entregada', 'Cancelada'], true)) {
            throw ValidationException::withMessages([
                'estado' => "La orden ya está en estado '{$orden->estado_orden}'.",
            ]);
        }

        DB::transaction(function () use ($orden, $idUsuario, $motivo) {
            // Liberar reservas de refacciones si existen
            $orden->load('equipo');
            $this->liberarReservas($orden->id_orden);

            $this->cambiarEstado($orden, 'Cancelada', $idUsuario, $motivo);
            $this->bitacora->cancelacionOrden($orden->id_orden, $motivo);
        });
    }

    // ── Helpers privados ─────────────────────────────────────────────────────

    private function generarFolio(string $prefijo = 'OS'): string
    {
        $fecha = now()->format('Ymd');
        do {
            $sufijo = strtoupper(Str::random(5));
            $folio  = "{$prefijo}-{$fecha}-{$sufijo}";
        } while (OrdenServicio::where('folio', $folio)->exists());

        return $folio;
    }

    private function verificarLimiteEquipos(int $idCliente): void
    {
        $ordenesActivas = OrdenServicio::whereHas('equipo', fn ($q) => $q->where('id_cliente', $idCliente))
            ->whereNotIn('estado_orden', ['Entregada', 'Cancelada'])
            ->count();

        if ($ordenesActivas >= 1) {
            $autorizado = SolicitudAutorizacion::where('id_cliente', $idCliente)
                ->where('tipo', 'multiple_equipo')
                ->where('estado', 'Aprobada')
                ->whereDate('created_at', '>=', today()->subDays(30))
                ->exists();

            if (! $autorizado) {
                throw ValidationException::withMessages([
                    'autorizacion' => 'El cliente ya tiene una orden activa. Para ingresar múltiples equipos se requiere autorización del administrador.',
                ]);
            }
        }
    }

    private function registrarEvidencias(OrdenServicio $orden, array $archivos, int $idUsuario): void
    {
        foreach ($archivos as $archivo) {
            if (! ($archivo instanceof UploadedFile)) continue;

            $ruta = Storage::putFile("evidencias/{$orden->id_orden}", $archivo);
            EvidenciaIngreso::create([
                'id_orden'            => $orden->id_orden,
                'ruta_archivo'        => $ruta,
                'tipo_archivo'        => $archivo->getMimeType(),
                'descripcion'         => 'Evidencia de ingreso',
                'id_usuario_registro' => $idUsuario,
            ]);
        }
    }

    private function registrarHistorial(
        OrdenServicio $orden,
        ?string $anterior,
        string $nuevo,
        int $idUsuario,
        ?string $comentario,
    ): void {
        $orden->historialEstados()->create([
            'id_usuario'     => $idUsuario,
            'estado_anterior' => $anterior,
            'estado_nuevo'   => $nuevo,
            'comentario'     => $comentario,
            'fecha_cambio'   => now(),
        ]);
    }

    private function liberarReservas(int $idOrden): void
    {
        \App\Models\ReservaRefaccion::where('id_orden', $idOrden)
            ->where('estado', 'Reservada')
            ->each(function ($reserva) {
                $reserva->refaccion->increment('existencia', $reserva->cantidad);
                $reserva->update(['estado' => 'Liberada']);
            });
    }
}
