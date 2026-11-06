<?php

namespace App\Services;

use App\Models\AprobacionAdmin;
use App\Models\HistorialEstado;
use App\Models\OrdenServicio;
use App\Models\PrestamoHerramienta;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class AprobacionAdminService
{
    /**
     * Emite una solicitud de autorización para el Administrador.
     * Tipos válidos: 'Exceso_Equipos', 'Exceso_Herramientas', 'Exceso_Refacciones'.
     */
    public function solicitarAprobacion(
        int $ordenId,
        string $tipoSolicitud,
        ?string $motivo = null
    ): AprobacionAdmin {
        $tiposPermitidos = ['Exceso_Equipos', 'Exceso_Herramientas', 'Exceso_Refacciones'];

        if (!in_array($tipoSolicitud, $tiposPermitidos, true)) {
            throw new InvalidArgumentException("Tipo de solicitud no válido: [{$tipoSolicitud}].");
        }

        return DB::transaction(function () use ($ordenId, $tipoSolicitud, $motivo) {
            $orden = OrdenServicio::findOrFail($ordenId);
            $orden->update(['requiere_aprobacion_admin' => true]);

            $solicitud = AprobacionAdmin::create([
                'admin_id' => null,
                'orden_servicio_id' => $orden->id,
                'tipo_solicitud' => $tipoSolicitud,
                'estado' => 'Pendiente',
                'motivo' => $motivo ?? "Solicitud de autorización por {$tipoSolicitud}",
            ]);

            HistorialEstado::create([
                'orden_servicio_id' => $orden->id,
                'modulo' => 'Administrador',
                'estado' => $orden->estado,
                'comentarios' => "Generada solicitud de aprobación administrativa: [{$tipoSolicitud}].",
                'usuario_id' => $orden->recepcion_id ?? $orden->tecnico_id,
            ]);

            return $solicitud;
        });
    }

    /**
     * Resuelve una solicitud administrativa (Aprobado / Rechazado) por un Administrador.
     */
    public function resolverSolicitud(
        int $solicitudId,
        int $adminId,
        string $estado,
        ?string $motivoResolucion = null
    ): AprobacionAdmin {
        $estado = ucfirst(strtolower($estado));
        if (!in_array($estado, ['Aprobado', 'Rechazado'], true)) {
            throw new InvalidArgumentException("Estado de resolución inválido [{$estado}]. Use 'Aprobado' o 'Rechazado'.");
        }

        return DB::transaction(function () use ($solicitudId, $adminId, $estado, $motivoResolucion) {
            $solicitud = AprobacionAdmin::with('ordenServicio')->findOrFail($solicitudId);
            $admin = User::findOrFail($adminId);

            $motivoCompleto = $solicitud->motivo;
            if ($motivoResolucion) {
                $motivoCompleto .= " | Resolución [{$estado}] por {$admin->name}: {$motivoResolucion}";
            }

            $solicitud->update([
                'admin_id' => $admin->id,
                'estado' => $estado,
                'motivo' => $motivoCompleto,
            ]);

            $orden = $solicitud->ordenServicio;

            // Si es por exceso de herramientas y fue aprobado, actualizar el préstamo
            if ($solicitud->tipo_solicitud === 'Exceso_Herramientas') {
                $prestamo = PrestamoHerramienta::where('orden_servicio_id', $orden->id)
                    ->where('estado_prestamo', 'Solicitado')
                    ->latest()
                    ->first();

                if ($prestamo) {
                    if ($estado === 'Aprobado') {
                        $prestamo->update(['estado_prestamo' => 'Aprobado_Admin']);
                        // Descontar del inventario
                        $prestamo->herramienta->decrement('stock_disponible', $prestamo->cantidad_prestada);
                    } else {
                        $prestamo->update(['estado_prestamo' => 'Devuelto']);
                    }
                }
            }

            // Verificar si quedan otras solicitudes pendientes para la orden
            $quedanPendientes = AprobacionAdmin::where('orden_servicio_id', $orden->id)
                ->where('estado', 'Pendiente')
                ->exists();

            if (!$quedanPendientes) {
                $orden->update(['requiere_aprobacion_admin' => false]);
            }

            HistorialEstado::create([
                'orden_servicio_id' => $orden->id,
                'modulo' => 'Administrador',
                'estado' => $orden->estado,
                'comentarios' => "Solicitud [{$solicitud->tipo_solicitud}] {$estado} por {$admin->name}.",
                'usuario_id' => $adminId,
            ]);

            return $solicitud->fresh(['admin', 'ordenServicio']);
        });
    }

    /**
     * Obtiene todas las solicitudes pendientes de resolución para el tablero del administrador.
     */
    public function obtenerSolicitudesPendientes(): Collection
    {
        return AprobacionAdmin::with(['ordenServicio.equipo.cliente.user'])
            ->where('estado', 'Pendiente')
            ->latest()
            ->get();
    }
}

