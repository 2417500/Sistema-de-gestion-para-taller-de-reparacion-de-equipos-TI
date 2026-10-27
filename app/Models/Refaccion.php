<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Refaccion extends Model
{
    protected $table = 'refacciones';

    protected $primaryKey = 'id_refaccion';

    protected $fillable = [
        'nombre', 'marca', 'numero_parte', 'descripcion', 'existencia',
        'existencia_minima', 'costo_unitario', 'precio_venta', 'ubicacion', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'existencia' => 'integer',
            'existencia_minima' => 'integer',
            'costo_unitario' => 'decimal:2',
            'precio_venta' => 'decimal:2',
            'estado' => 'boolean',
        ];
    }

    public function refaccionesUtilizadas(): HasMany
    {
        return $this->hasMany(RefaccionUtilizada::class, 'id_refaccion', 'id_refaccion');
    }

    public function movimientosInventario(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'id_refaccion', 'id_refaccion');
    }

    public function detallesCotizacion(): HasMany
    {
        return $this->hasMany(DetalleCotizacion::class, 'id_refaccion', 'id_refaccion');
    }

    public function getStockBajoAttribute(): bool
    {
        return $this->existencia <= $this->existencia_minima;
    }
}
