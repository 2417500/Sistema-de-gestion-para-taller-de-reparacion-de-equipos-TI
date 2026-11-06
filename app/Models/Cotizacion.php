<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizacions';

    protected $fillable = [
        'orden_servicio_id',
        'version',
        'costo_mano_obra',
        'subtotal',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'costo_mano_obra' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function ordenServicio()
    {
        return $this->belongsTo(OrdenServicio::class, 'orden_servicio_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCotizacion::class, 'cotizacion_id');
    }

    /**
     * Recalcula el subtotal sumando el costo de mano de obra y el subtotal de cada refacción.
     */
    public function calcularSubtotal(): float
    {
        $totalRefacciones = (float) $this->detalles()->sum('subtotal');
        $this->subtotal = round((float) $this->costo_mano_obra + $totalRefacciones, 2);
        $this->save();

        return (float) $this->subtotal;
    }
}
