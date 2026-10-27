<?php

namespace App\Services;

use App\Models\MovimientoInventario;
use App\Models\Refaccion;
use App\Models\ReservaRefaccion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * InventarioService
 * -----------------
 * Controla las existencias, reservas y movimientos de refacciones.
 *
 * Reglas de negocio cubiertas:
 *  - El almacén registra entradas y salidas de refacciones.
 *  - Las refacciones reservadas para una orden no se asignan a otra si no hay suficiente.
 *  - Si una orden se cancela, las reservas se liberan.
 *  - Si el stock llega al mínimo, se notifica al administrador.
 *  - Solo se entregan piezas para reparaciones autorizadas.
 */
class InventarioService
{
    public function __construct(
        private readonly BitacoraService     $bitacora,
        private readonly NotificacionService $notificaciones,
    ) {}

    // ── Movimientos de inventario ─────────────────────────────────────────────

    /**
     * Registra una entrada de inventario (compra, reposición, devolución).
     */
    public function registrarEntrada(
        int $idRefaccion,
        int $idUsuario,
        int $cantidad,
        float $costoUnitario,
        string $motivo,
        ?string $referencia = null,
    ): MovimientoInventario {
        return DB::transaction(function () use ($idRefaccion, $idUsuario, $cantidad, $costoUnitario, $motivo, $referencia) {
            $refaccion = Refaccion::lockForUpdate()->findOrFail($idRefaccion);
            $refaccion->increment('existencia', $cantidad);

            $movimiento = MovimientoInventario::create([
                'id_refaccion'    => $idRefaccion,
                'id_usuario'      => $idUsuario,
                'tipo_movimiento' => 'Entrada',
                'cantidad'        => $cantidad,
                'costo_unitario'  => $costoUnitario,
                'motivo'          => $motivo,
                'referencia'      => $referencia,
                'fecha_movimiento' => now(),
            ]);

            $this->bitacora->movimientoInventario($movimiento->id_movimiento_inventario, 'Entrada', $idRefaccion, $cantidad);

            return $movimiento;
        });
    }

    /**
     * Registra una salida de inventario (baja, merma, ajuste).
     */
    public function registrarSalida(
        int $idRefaccion,
        int $idUsuario,
        int $cantidad,
        string $motivo,
        ?string $referencia = null,
    ): MovimientoInventario {
        return DB::transaction(function () use ($idRefaccion, $idUsuario, $cantidad, $motivo, $referencia) {
            $refaccion = Refaccion::lockForUpdate()->findOrFail($idRefaccion);
            $this->verificarDisponibilidad($refaccion, $cantidad);
            $refaccion->decrement('existencia', $cantidad);

            $movimiento = MovimientoInventario::create([
                'id_refaccion'    => $idRefaccion,
                'id_usuario'      => $idUsuario,
                'tipo_movimiento' => 'Salida',
                'cantidad'        => $cantidad,
                'costo_unitario'  => null,
                'motivo'          => $motivo,
                'referencia'      => $referencia,
                'fecha_movimiento' => now(),
            ]);

            $this->verificarStockMinimo($refaccion);
            $this->bitacora->movimientoInventario($movimiento->id_movimiento_inventario, 'Salida', $idRefaccion, $cantidad);

            return $movimiento;
        });
    }

    // ── Reservas ─────────────────────────────────────────────────────────────

    /**
     * Reserva las refacciones para una orden antes de iniciar la reparación.
     * No descuenta el inventario; solo "bloquea" las piezas.
     *
     * @param  int  $idOrden
     * @param  array<int, int>  $refacciones  [id_refaccion => cantidad]
     */
    public function reservar(int $idOrden, array $refacciones, int $idUsuario): void
    {
        DB::transaction(function () use ($idOrden, $refacciones, $idUsuario) {
            foreach ($refacciones as $idRefaccion => $cantidad) {
                $refaccion = Refaccion::lockForUpdate()->findOrFail($idRefaccion);

                $yaReservado = ReservaRefaccion::where('id_refaccion', $idRefaccion)
                    ->where('estado', 'Reservada')
                    ->sum('cantidad');

                $disponible = $refaccion->existencia - $yaReservado;

                if ($disponible < $cantidad) {
                    throw ValidationException::withMessages([
                        "refaccion_{$idRefaccion}" => "Existencia insuficiente de '{$refaccion->nombre}'. Disponible: {$disponible}, requerida: {$cantidad}.",
                    ]);
                }

                ReservaRefaccion::create([
                    'id_orden'    => $idOrden,
                    'id_refaccion' => $idRefaccion,
                    'cantidad'    => $cantidad,
                    'estado'      => 'Reservada',
                ]);

                $this->bitacora->registrar('reserva_refaccion', 'ReservaRefaccion', $idRefaccion,
                    "Orden {$idOrden}: {$cantidad} pza(s) reservadas", $idUsuario);
            }
        });
    }

    /**
     * Libera las reservas de una orden (cancelación o cierre sin uso).
     */
    public function liberarReservas(int $idOrden, int $idUsuario): void
    {
        $reservas = ReservaRefaccion::where('id_orden', $idOrden)->where('estado', 'Reservada')->get();

        DB::transaction(function () use ($reservas, $idOrden, $idUsuario) {
            foreach ($reservas as $reserva) {
                $reserva->update(['estado' => 'Liberada']);
                $this->bitacora->registrar('liberacion_reserva', 'ReservaRefaccion', $reserva->id_reserva,
                    "Orden {$idOrden} cancelada", $idUsuario);
            }
        });
    }

    /**
     * Entrega las piezas reservadas al técnico para su uso en reparación.
     * Descuenta del inventario físico y marca la reserva como entregada.
     */
    public function entregarParaReparacion(int $idOrden, int $idRefaccion, int $cantidad): void
    {
        DB::transaction(function () use ($idOrden, $idRefaccion, $cantidad) {
            $reserva = ReservaRefaccion::where('id_orden', $idOrden)
                ->where('id_refaccion', $idRefaccion)
                ->where('estado', 'Reservada')
                ->first();

            $refaccion = Refaccion::lockForUpdate()->findOrFail($idRefaccion);

            if ($reserva && $reserva->cantidad >= $cantidad) {
                // Usar de la reserva
                $reserva->decrement('cantidad', $cantidad);
                if ($reserva->cantidad === 0) {
                    $reserva->update(['estado' => 'Entregada']);
                }
            } else {
                // Sin reserva suficiente: verificar inventario libre
                $this->verificarDisponibilidad($refaccion, $cantidad);
            }

            $refaccion->decrement('existencia', $cantidad);
            $this->verificarStockMinimo($refaccion);
        });
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function verificarDisponibilidad(Refaccion $refaccion, int $cantidad): void
    {
        if ($refaccion->existencia < $cantidad) {
            throw ValidationException::withMessages([
                'refaccion' => "Existencia insuficiente de '{$refaccion->nombre}'. Disponible: {$refaccion->existencia}.",
            ]);
        }
    }

    private function verificarStockMinimo(Refaccion $refaccion): void
    {
        $refaccion->refresh();
        if ($refaccion->existencia <= $refaccion->existencia_minima) {
            $this->notificaciones->stockBajo($refaccion->nombre, $refaccion->existencia);
        }
    }
}
