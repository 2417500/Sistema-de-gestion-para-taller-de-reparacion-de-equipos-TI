<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPago extends Model
{
    use HasFactory;

    protected $table = 'ticket_pagos';

    protected $fillable = [
        'orden_servicio_id',
        'subtotal',
        'porcentaje_descuento',
        'monto_descuento',
        'total_pagar',
        'metodo_pago',
        'fecha_pago',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'porcentaje_descuento' => 'decimal:2',
            'monto_descuento' => 'decimal:2',
            'total_pagar' => 'decimal:2',
            'fecha_pago' => 'datetime',
        ];
    }

    public function ordenServicio()
    {
        return $this->belongsTo(OrdenServicio::class, 'orden_servicio_id');
    }

    /**
     * Aplica el porcentaje de descuento y calcula montos finales.
     */
    public function aplicarDescuento(float $porcentaje): void
    {
        $this->porcentaje_descuento = round($porcentaje, 2);
        $this->monto_descuento = round(((float)$this->subtotal * $this->porcentaje_descuento) / 100, 2);
        $this->total_pagar = round((float)$this->subtotal - (float)$this->monto_descuento, 2);
    }
}
