<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refaccion extends Model
{
    use HasFactory;

    protected $table = 'refaccions';

    protected $fillable = [
        'nombre',
        'marca',
        'modelo',
        'precio_unitario',
        'stock_disponible',
    ];

    protected function casts(): array
    {
        return [
            'precio_unitario' => 'decimal:2',
            'stock_disponible' => 'integer',
        ];
    }

    public function detallesCotizacion()
    {
        return $this->hasMany(DetalleCotizacion::class, 'refaccion_id');
    }

    public function scopeConStock($query)
    {
        return $query->where('stock_disponible', '>', 0);
    }

    public function descontarStock(int $cantidad): bool
    {
        if ($this->stock_disponible < $cantidad) {
            return false;
        }

        $this->decrement('stock_disponible', $cantidad);
        return true;
    }

    public function restituirStock(int $cantidad): void
    {
        $this->increment('stock_disponible', $cantidad);
    }
}
