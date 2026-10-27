<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $primaryKey = 'id_cotizacion';

    protected $fillable = ['id_orden', 'estado', 'observaciones'];

    public function orden(): BelongsTo
    {
        return $this->belongsTo(OrdenServicio::class, 'id_orden', 'id_orden');
    }

    public function versiones(): HasMany
    {
        return $this->hasMany(VersionCotizacion::class, 'id_cotizacion', 'id_cotizacion');
    }

    public function versionActual(): HasOne
    {
        return $this->hasOne(VersionCotizacion::class, 'id_cotizacion', 'id_cotizacion')
            ->ofMany('numero_version', 'max');
    }
}
