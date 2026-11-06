<?php

namespace App\Services;

use App\Models\HistorialEstado;
use App\Models\OrdenServicio;
use App\Models\TicketPago;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class FacturacionService
{
    /**
     * Calcula y emite el ticket de pago/factura preliminar de una orden de servicio.
     * Regla de Negocio Crítica: Si el cliente ha superado 5 equipos reparados
     * (equipos_reparados_count > 5), se aplica un 15% de descuento directo en el ticket.
     */
    public function generarTicket(int $ordenId, string $metodoPago = 'Efectivo'): TicketPago
    {
        $metodoPago = ucfirst(strtolower($metodoPago));
        if (!in_array($metodoPago, ['Efectivo', 'Tarjeta'], true)) {
            throw new InvalidArgumentException("Método de pago inválido [{$metodoPago}]. Se permite 'Efectivo' o 'Tarjeta'.");
        }

        return DB::transaction(function () use ($ordenId, $metodoPago) {
            $orden = OrdenServicio::with(['equipo.cliente', 'cotizaciones.detalles'])->findOrFail($ordenId);

            // Obtener subtotal de la última cotización aceptada o vigente
            $cotizacion = $orden->cotizaciones()->where('estado', 'Aceptada')->latest()->first()
                ?? $orden->cotizaciones()->latest()->first();

            $subtotal = $cotizacion ? (float) $cotizacion->subtotal : 0.00;

            if ($subtotal <= 0) {
                // Si no hay cotización o es 0, buscar costo en diagnóstico
                $diagnostico = $orden->diagnosticos()->latest()->first();
                $subtotal = $diagnostico ? (float) $diagnostico->costo_diagnostico : 0.00;
            }

            // Validar fidelidad y descuento del 15%
            $cliente = $orden->equipo?->cliente;
            $aplicaDescuento = false;
            $porcentajeDescuento = 0.00;

            if ($cliente) {
                $aplicaDescuento = ($cliente->equipos_reparados_count > 5) || (bool) $cliente->aplica_descuento;
                if ($aplicaDescuento) {
                    $porcentajeDescuento = 15.00;
                }
            }

            $montoDescuento = round(($subtotal * $porcentajeDescuento) / 100, 2);
            $totalPagar = round($subtotal - $montoDescuento, 2);

            $ticket = TicketPago::updateOrCreate(
                ['orden_servicio_id' => $orden->id],
                [
                    'subtotal' => $subtotal,
                    'porcentaje_descuento' => $porcentajeDescuento,
                    'monto_descuento' => $montoDescuento,
                    'total_pagar' => $totalPagar,
                    'metodo_pago' => $metodoPago,
                    'fecha_pago' => now(),
                ]
            );

            return $ticket->load('ordenServicio.equipo.cliente');
        });
    }

    /**
     * Procesa el cobro final, registra la entrega del equipo al cliente e incrementa
     * el contador de equipos reparados del cliente para la regla de fidelidad.
     */
    public function procesarCobroYEntrega(int $ordenId, string $metodoPago = 'Efectivo', ?int $usuarioId = null): TicketPago
    {
        return DB::transaction(function () use ($ordenId, $metodoPago, $usuarioId) {
            $orden = OrdenServicio::with('equipo.cliente')->findOrFail($ordenId);

            // 1. Generar o actualizar ticket de cobro
            $ticket = $this->generarTicket($ordenId, $metodoPago);

            // 2. Marcar orden de servicio como Entregado
            $orden->update(['estado' => 'Entregado']);

            // 3. Incrementar equipos_reparados_count del cliente y actualizar elegibilidad de descuento
            $cliente = $orden->equipo?->cliente;
            if ($cliente) {
                $cliente->increment('equipos_reparados_count');
                $cliente->actualizarElegibilidadDescuento();
            }

            // 4. Registrar en historial de auditoría
            $operadorId = $usuarioId ?? $orden->recepcion_id;
            HistorialEstado::create([
                'orden_servicio_id' => $orden->id,
                'modulo' => 'Facturación',
                'estado' => 'Entregado',
                'comentarios' => "Cobro liquidado en {$metodoPago}. Total: $" . number_format($ticket->total_pagar, 2) . 
                    ($ticket->porcentaje_descuento > 0 ? " (Descuento de fidelidad del {$ticket->porcentaje_descuento}% aplicado)" : "") . 
                    ". Equipo entregado formalmente al cliente.",
                'usuario_id' => $operadorId,
            ]);

            return $ticket->fresh(['ordenServicio.equipo.cliente']);
        });
    }

    /**
     * Obtiene el ticket de pago emitido de una orden.
     */
    public function obtenerTicket(int $ordenId): ?TicketPago
    {
        return TicketPago::with('ordenServicio.equipo.cliente')->where('orden_servicio_id', $ordenId)->first();
    }
}

