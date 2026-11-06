<?php

namespace App\Services;

use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\HistorialEstado;
use App\Models\OrdenServicio;
use App\Models\Refaccion;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CotizacionService
{
    /**
     * Elabora una nueva versión de cotización/presupuesto con mano de obra y refacciones.
     *
     * @param int $ordenId
     * @param float $costoManoObra
     * @param array $repuestos Lista de [refaccion_id, cantidad, precio_unitario (opcional)]
     */
    public function crearCotizacion(int $ordenId, float $costoManoObra, array $repuestos = []): Cotizacion
    {
        return DB::transaction(function () use ($ordenId, $costoManoObra, $repuestos) {
            $orden = OrdenServicio::findOrFail($ordenId);

            // Determinar versión incremental para trazabilidad
            $ultimaVersion = Cotizacion::where('orden_servicio_id', $orden->id)->max('version') ?? 0;
            $nuevaVersion = $ultimaVersion + 1;

            $cotizacion = Cotizacion::create([
                'orden_servicio_id' => $orden->id,
                'version' => $nuevaVersion,
                'costo_mano_obra' => round($costoManoObra, 2),
                'subtotal' => round($costoManoObra, 2),
                'estado' => 'Pendiente',
            ]);

            $totalRefacciones = 0.0;

            foreach ($repuestos as $item) {
                $refaccion = Refaccion::findOrFail($item['refaccion_id']);
                $cantidad = (int) $item['cantidad'];
                $precioUnitario = isset($item['precio_unitario']) 
                    ? (float) $item['precio_unitario'] 
                    : (float) $refaccion->precio_unitario;

                $subtotalLinea = round($cantidad * $precioUnitario, 2);
                $totalRefacciones += $subtotalLinea;

                DetalleCotizacion::create([
                    'cotizacion_id' => $cotizacion->id,
                    'refaccion_id' => $refaccion->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotalLinea,
                ]);
            }

            // Actualizar el subtotal consolidado
            $cotizacion->subtotal = round($costoManoObra + $totalRefacciones, 2);
            $cotizacion->save();

            HistorialEstado::create([
                'orden_servicio_id' => $orden->id,
                'modulo' => 'Técnico',
                'estado' => $orden->estado,
                'comentarios' => "Cotización v{$nuevaVersion} generada por un subtotal de $" . number_format($cotizacion->subtotal, 2),
                'usuario_id' => $orden->tecnico_id ?? $orden->recepcion_id,
            ]);

            return $cotizacion->load(['detalles.refaccion', 'ordenServicio']);
        });
    }

    /**
     * Procesa la respuesta del cliente sobre la cotización (Aceptada / Rechazada).
     */
    public function responderCotizacion(int $cotizacionId, string $decision, int $usuarioId): Cotizacion
    {
        $decision = ucfirst(strtolower($decision));
        if (!in_array($decision, ['Aceptada', 'Rechazada'], true)) {
            throw new InvalidArgumentException("Decisión inválida [{$decision}]. Solo se permite 'Aceptada' o 'Rechazada'.");
        }

        return DB::transaction(function () use ($cotizacionId, $decision, $usuarioId) {
            $cotizacion = Cotizacion::with('ordenServicio')->findOrFail($cotizacionId);
            $cotizacion->estado = $decision;
            $cotizacion->save();

            $orden = $cotizacion->ordenServicio;

            if ($decision === 'Aceptada') {
                $orden->estado = 'En_Proceso';
                $orden->save();
            }

            HistorialEstado::create([
                'orden_servicio_id' => $orden->id,
                'modulo' => 'Cliente',
                'estado' => $orden->estado,
                'comentarios' => "El cliente ha {$decision} la cotización v{$cotizacion->version} (Monto: $" . number_format($cotizacion->subtotal, 2) . ")",
                'usuario_id' => $usuarioId,
            ]);

            return $cotizacion->fresh();
        });
    }

    /**
     * Calcula el monto total sumando mano de obra y detalles.
     */
    public function calcularTotal(Cotizacion $cotizacion): float
    {
        return $cotizacion->calcularSubtotal();
    }
}

