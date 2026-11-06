<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Herramienta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'marca',
        'modelo',
        'numero_serie',
        'stock_total',
        'stock_disponible',
    ];

    protected function casts(): array
    {
        return [
            'stock_total' => 'integer',
            'stock_disponible' => 'integer',
        ];
    }

    public function prestamos()
    {
        return $this->hasMany(PrestamoHerramienta::class, 'herramienta_id');
    }

    public function scopeDisponibles($query)
    {
        return $query->where('stock_disponible', '>', 0);
    }

    public function prestar(int $cantidad): bool
    {
        if ($this->stock_disponible < $cantidad) {
            return false;
        }

        $this->decrement('stock_disponible', $cantidad);
        return true;
    }

    public function devolver(int $cantidad): void
    {
        $this->increment('stock_disponible', $cantidad);
    }
}
