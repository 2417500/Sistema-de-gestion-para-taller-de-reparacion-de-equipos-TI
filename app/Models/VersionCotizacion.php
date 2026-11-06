<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VersionCotizacion extends Model
{
    protected $table = 'versiones_cotizacion';

    protected $primaryKey = 'id_version';

    protected $fillable = [
        'id_cotizacion', 'id_regla_descuento', 'id_usuario', 'numero_version',
        'subtotal', 'descuento', 'porcentaje_descuento_aplicado', 'total',
        'condiciones', 'fecha_emision', 'fecha_vencimiento',
    ];

    protected function casts(): array
    {
        return [
            'numero_version' => 'integer',
            'subtotal' => 'decimal:2',
            'descuento' => 'decimal:2',
            'porcentaje_descuento_aplicado' => 'decimal:2',
            'total' => 'decimal:2',
            'fecha_emision' => 'datetime',
            'fecha_vencimiento' => 'datetime',
        ];
    }

    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion', 'id_cotizacion');
    }

    public function reglaDescuento(): BelongsTo
    {
        return $this->belongsTo(ReglaDescuento::class, 'id_regla_descuento', 'id_regla_descuento');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleCotizacion::class, 'id_version', 'id_version');
    }

    public function aprobacion(): HasOne
    {
        return $this->hasOne(Aprobacion::class, 'id_version', 'id_version');
    }
}
