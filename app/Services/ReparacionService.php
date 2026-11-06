<?php

namespace App\Services;

use App\Models\IncidenciaReparacion;
use App\Models\OrdenServicio;
use App\Models\PruebaFinal;
use App\Models\Reparacion;
use App\Models\RefaccionUtilizada;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * ReparacionService
 * -----------------
 * Gestiona el ciclo de vida de una reparación.
 *
 * Reglas de negocio cubiertas:
 *  - Ninguna reparación presupuestada inicia sin cotización aceptada y anticipo.
 *  - El técnico registra tareas, tiempos, refacciones e incidencias.
 *  - Fallas adicionales o refacciones extra requieren aprobación del cliente.
 *  - Pruebas finales obligatorias antes de marcar como listo.
 *  - Una orden en reparación no puede cancelarse ordinariamente.
 */
class ReparacionService
{
    public function __construct(
        private readonly BitacoraService     $bitacora,
        private readonly NotificacionService $notificaciones,
        private readonly CotizacionService   $cotizaciones,
        private readonly PagoService         $pagos,
        private readonly InventarioService   $inventario,
    ) {}

    // ── Inicio ───────────────────────────────────────────────────────────────

    /**
     * Inicia la reparación validando todas las condiciones previas.
     */
    public function iniciar(OrdenServicio $orden, int $idTecnico): Reparacion
    {
        // Validar cotización aceptada
        $this->cotizaciones->validarCondicionesParaReparacion($orden);

        // Validar anticipo pagado
        $this->pagos->validarAnticipo($orden);

        if ($orden->estado_orden === 'En Reparación') {
            throw ValidationException::withMessages(['estado' => 'La orden ya tiene una reparación en curso.']);
        }

        return DB::transaction(function () use ($orden, $idTecnico) {
            $reparacion = Reparacion::create([
                'id_orden'   => $orden->id_orden,
                'id_tecnico' => $idTecnico,
                'fecha_inicio' => now(),
                'estado'     => 'En Proceso',
            ]);

            $orden->update(['estado_orden' => 'En Reparación']);
            $orden->historialEstados()->create([
                'id_usuario'     => $idTecnico,
                'estado_anterior' => $orden->estado_orden,
                'estado_nuevo'   => 'En Reparación',
                'comentario'     => 'Reparación iniciada',
                'fecha_cambio'   => now(),
            ]);
            $this->bitacora->cambioEstadoOrden($orden->id_orden, $orden->estado_orden, 'En Reparación', $idTecnico);

            return $reparacion;
        });
    }

    // ── Registro de trabajo ──────────────────────────────────────────────────

    /**
     * Actualiza los datos de trabajo del técnico (texto y estado).
     */
    public function actualizarTrabajo(Reparacion $reparacion, array $datos): void
    {
        $reparacion->update([
            'trabajo_realizado' => $datos['trabajo_realizado'] ?? $reparacion->trabajo_realizado,
            'observaciones'     => $datos['observaciones'] ?? $reparacion->observaciones,
            'estado'            => $datos['estado'] ?? $reparacion->estado,
        ]);

        $this->bitacora->registrar('actualizacion_reparacion', 'Reparacion', $reparacion->id_reparacion,
            $datos['trabajo_realizado'] ?? null);
    }

    /**
     * Registra una refacción utilizada y descuenta del inventario.
     */
    public function agregarRefaccionUtilizada(
        Reparacion $reparacion,
        int $idRefaccion,
        int $cantidad,
        float $precioUnitario,
    ): RefaccionUtilizada {
        return DB::transaction(function () use ($reparacion, $idRefaccion, $cantidad, $precioUnitario) {
            // Verificar disponibilidad en reservas primero; luego descontar inventario
            $this->inventario->entregarParaReparacion($reparacion->id_orden, $idRefaccion, $cantidad);

            $importe = round($cantidad * $precioUnitario, 2);
            $ru = RefaccionUtilizada::create([
                'id_reparacion'   => $reparacion->id_reparacion,
                'id_refaccion'    => $idRefaccion,
                'cantidad'        => $cantidad,
                'precio_unitario' => $precioUnitario,
                'importe'         => $importe,
            ]);

            $this->bitacora->movimientoInventario(0, 'salida_reparacion', $idRefaccion, $cantidad);

            return $ru;
        });
    }

    // ── Incidencias ──────────────────────────────────────────────────────────

    /**
     * Registra una incidencia o falla adicional durante la reparación.
     * Si requiere aprobación del cliente, lo notifica.
     */
    public function registrarIncidencia(
        Reparacion $reparacion,
        int $idTecnico,
        string $tipo,
        string $descripcion,
        bool $requiereAprobacionCliente = false,
    ): IncidenciaReparacion {
        $incidencia = IncidenciaReparacion::create([
            'id_reparacion'             => $reparacion->id_reparacion,
            'id_tecnico'                => $idTecnico,
            'tipo'                      => $tipo,
            'descripcion'               => $descripcion,
            'estado'                    => $requiereAprobacionCliente ? 'PendienteCliente' : 'Abierta',
            'requiere_aprobacion_cliente' => $requiereAprobacionCliente,
        ]);

        $this->bitacora->registrar('incidencia_registrada', 'Reparacion', $reparacion->id_reparacion, $descripcion, $idTecnico);

        if ($requiereAprobacionCliente) {
            $idCliente = $reparacion->orden?->equipo?->id_cliente;
            if ($idCliente) {
                $folio = $reparacion->orden->folio ?? "#{$reparacion->id_orden}";
                $this->notificaciones->incidenciaRequiereAprobacion($idCliente, $folio, $descripcion);
            }
        }

        return $incidencia;
    }

    // ── Finalización ─────────────────────────────────────────────────────────

    /**
     * Registra la prueba final y, si es exitosa, marca la reparación como terminada.
     */
    public function registrarPruebaFinal(
        Reparacion $reparacion,
        int $idTecnico,
        bool $equipoReparado,
        string $descripcionPrueba,
        ?string $resultado = null,
        ?string $causaNoReparado = null,
    ): PruebaFinal {
        return DB::transaction(function () use ($reparacion, $idTecnico, $equipoReparado, $descripcionPrueba, $resultado, $causaNoReparado) {
            $prueba = PruebaFinal::create([
                'id_reparacion'    => $reparacion->id_reparacion,
                'id_tecnico'       => $idTecnico,
                'equipo_reparado'  => $equipoReparado,
                'descripcion_prueba' => $descripcionPrueba,
                'resultado'        => $resultado,
                'causa_no_reparado' => $causaNoReparado,
                'fecha_prueba'     => now(),
            ]);

            if ($equipoReparado) {
                $reparacion->update(['estado' => 'Terminada', 'fecha_fin' => now()]);
                $orden = $reparacion->orden;
                if ($orden) {
                    $orden->update(['estado_orden' => 'Lista']);
                    $idCliente = $orden->equipo?->id_cliente;
                    if ($idCliente) {
                        $this->notificaciones->equipoListoParaEntrega($idCliente, $orden->folio ?? "#{$orden->id_orden}");
                    }
                }
            }

            $this->bitacora->registrar('prueba_final', 'Reparacion', $reparacion->id_reparacion,
                $equipoReparado ? 'Equipo reparado' : "No reparado: {$causaNoReparado}", $idTecnico);

            return $prueba;
        });
    }
}
