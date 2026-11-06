<?php

namespace App\Services;

use App\Models\Diagnostico;
use App\Models\HistorialEstado;
use App\Models\OrdenServicio;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DiagnosticoService
{
    /**
     * Registra el diagnóstico técnico de una orden de servicio.
     * Política de taller: Diagnóstico inicial gratuito (costo = 0.00).
     */
    public function registrarDiagnostico(
        int $ordenId,
        int $tecnicoId,
        string $hallazgos,
        string $solucionPropuesta,
        float $costo = 0.00
    ): Diagnostico {
        return DB::transaction(function () use ($ordenId, $tecnicoId, $hallazgos, $solucionPropuesta, $costo) {
            $orden = OrdenServicio::findOrFail($ordenId);
            $tecnico = User::findOrFail($tecnicoId);

            $diagnostico = Diagnostico::create([
                'orden_servicio_id' => $orden->id,
                'tecnico_id' => $tecnico->id,
                'hallazgos' => $hallazgos,
                'solucion_propuesta' => $solucionPropuesta,
                'costo_diagnostico' => $costo,
            ]);

            // Si la orden estaba pendiente, pasa a En_Proceso
            if ($orden->estado === 'Pendiente') {
                $orden->update(['estado' => 'En_Proceso']);
            }

            // Registrar en historial de auditoría
            HistorialEstado::create([
                'orden_servicio_id' => $orden->id,
                'modulo' => 'Técnico',
                'estado' => $orden->estado,
                'comentarios' => "Diagnóstico registrado por {$tecnico->name}: " . substr($hallazgos, 0, 80) . '...',
                'usuario_id' => $tecnicoId,
            ]);

            return $diagnostico->load(['ordenServicio', 'tecnico']);
        });
    }

    /**
     * Devuelve los diagnósticos emitidos para una orden de servicio.
     */
    public function obtenerDiagnosticosPorOrden(int $ordenId): Collection
    {
        return Diagnostico::with('tecnico')
            ->where('orden_servicio_id', $ordenId)
            ->latest()
            ->get();
    }
}

