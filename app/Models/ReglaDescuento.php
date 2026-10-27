<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReglaDescuento extends Model
{
    protected $table = 'reglas_descuento';

    protected $primaryKey = 'id_regla_descuento';

    protected $fillable = [
        'nombre', 'descripcion', 'reparaciones_requeridas', 'porcentaje_descuento', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'reparaciones_requeridas' => 'integer',
            'porcentaje_descuento' => 'decimal:2',
            'estado' => 'boolean',
        ];
    }

    public function versionesCotizacion(): HasMany
    {
        return $this->hasMany(VersionCotizacion::class, 'id_regla_descuento', 'id_regla_descuento');
    }
}
