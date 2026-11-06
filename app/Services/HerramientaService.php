<?php

namespace App\Services;

use App\Models\AprobacionAdmin;
use App\Models\Herramienta;
use App\Models\HistorialEstado;
use App\Models\OrdenServicio;
use App\Models\PrestamoHerramienta;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class HerramientaService
{
    /**
     * Solicita el préstamo de una herramienta de laboratorio para una orden de servicio.
     * Regla de negocio: Si se solicitan más de 2 unidades de una herramienta especializada,
     * se genera automáticamente una solicitud de aprobación administrativa (Exceso_Herramientas).
     */
    public function solicitarPrestamo(
        int $ordenId,
        int $herramientaId,
        int $tecnicoId,
        int $cantidad = 1
    ): PrestamoHerramienta {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException("La cantidad solicitada debe ser mayor a 0.");
        }

        return DB::transaction(function () use ($ordenId, $herramientaId, $tecnicoId, $cantidad) {
            $orden = OrdenServicio::findOrFail($ordenId);
            $herramienta = Herramienta::findOrFail($herramientaId);
            $tecnico = User::findOrFail($tecnicoId);

            if ($herramienta->stock_disponible < $cantidad) {
                throw new RuntimeException(
                    "Stock insuficiente para la herramienta [{$herramienta->nombre}]. Disponible: {$herramienta->stock_disponible}."
                );
            }

            // Si solicita más de 2 herramientas, requiere aprobación de administración
            $requiereAprobacion = $cantidad > 2;

            $prestamo = PrestamoHerramienta::create([
                'orden_servicio_id' => $orden->id,
                'herramienta_id' => $herramienta->id,
                'tecnico_id' => $tecnico->id,
                'cantidad_prestada' => $cantidad,
                'estado_prestamo' => $requiereAprobacion ? 'Solicitado' : 'Aprobado_Admin',
                'fecha_prestamo' => now(),
            ]);

            if ($requiereAprobacion) {
                $orden->update(['requiere_aprobacion_admin' => true]);

                AprobacionAdmin::create([
                    'admin_id' => null,
                    'orden_servicio_id' => $orden->id,
                    'tipo_solicitud' => 'Exceso_Herramientas',
                    'estado' => 'Pendiente',
                    'motivo' => "El técnico {$tecnico->name} solicita {$cantidad} unidades de [{$herramienta->nombre}] para la orden {$orden->codigo_servicio}.",
                ]);

                HistorialEstado::create([
                    'orden_servicio_id' => $orden->id,
                    'modulo' => 'Almacén',
                    'estado' => $orden->estado,
                    'comentarios' => "Préstamo en espera de aprobación administrativa: {$cantidad}x {$herramienta->nombre}",
                    'usuario_id' => $tecnicoId,
                ]);
            } else {
                // Descontar inmediatamente stock disponible al aprobar
                $herramienta->decrement('stock_disponible', $cantidad);

                HistorialEstado::create([
                    'orden_servicio_id' => $orden->id,
                    'modulo' => 'Almacén',
                    'estado' => $orden->estado,
                    'comentarios' => "Préstamo de herramienta aprobado y registrado: {$cantidad}x {$herramienta->nombre}",
                    'usuario_id' => $tecnicoId,
                ]);
            }

            return $prestamo->load(['herramienta', 'tecnico', 'ordenServicio']);
        });
    }

    /**
     * Entrega formal de la herramienta física al técnico en la ventanilla de almacén.
     */
    public function entregarHerramienta(int $prestamoId): PrestamoHerramienta
    {
        $prestamo = PrestamoHerramienta::findOrFail($prestamoId);

        if ($prestamo->estado_prestamo === 'Solicitado') {
            throw new RuntimeException("El préstamo aún requiere aprobación administrativa antes de ser entregado.");
        }

        if ($prestamo->estado_prestamo === 'Devuelto') {
            throw new RuntimeException("El préstamo ya fue devuelto con anterioridad.");
        }

        $prestamo->update([
            'estado_prestamo' => 'Entregado',
            'fecha_prestamo' => now(),
        ]);

        return $prestamo->fresh(['herramienta', 'tecnico']);
    }

    /**
     * Registra la devolución de la herramienta al almacén y restituye el stock disponible.
     */
    public function devolverHerramienta(int $prestamoId): PrestamoHerramienta
    {
        return DB::transaction(function () use ($prestamoId) {
            $prestamo = PrestamoHerramienta::with('herramienta')->findOrFail($prestamoId);

            if ($prestamo->estado_prestamo === 'Devuelto') {
                return $prestamo;
            }

            // Restituir stock disponible de la herramienta
            $prestamo->herramienta->increment('stock_disponible', $prestamo->cantidad_prestada);

            $prestamo->update([
                'estado_prestamo' => 'Devuelto',
                'fecha_devolucion' => now(),
            ]);

            HistorialEstado::create([
                'orden_servicio_id' => $prestamo->orden_servicio_id,
                'modulo' => 'Almacén',
                'estado' => 'Devuelto',
                'comentarios' => "Herramienta [{$prestamo->herramienta->nombre}] devuelta al inventario ({$prestamo->cantidad_prestada} unidades).",
                'usuario_id' => $prestamo->tecnico_id,
            ]);

            return $prestamo->fresh(['herramienta']);
        });
    }

    /**
     * Consulta préstamos pendientes de devolución para un técnico.
     */
    public function obtenerPrestamosActivos(int $tecnicoId): Collection
    {
        return PrestamoHerramienta::with('herramienta', 'ordenServicio')
            ->where('tecnico_id', $tecnicoId)
            ->whereIn('estado_prestamo', ['Solicitado', 'Aprobado_Admin', 'Entregado'])
            ->get();
    }
}

