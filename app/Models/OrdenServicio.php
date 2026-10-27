<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrdenServicio extends Model
{
    protected $table = 'ordenes_servicio';

    protected $primaryKey = 'id_orden';

    protected $fillable = [
        'id_equipo', 'id_recepcionista', 'fecha_recepcion', 'falla_reportada',
        'accesorios_recibidos', 'estado_fisico_ingreso', 'estado_orden', 'observaciones',
    ];

    protected function casts(): array
    {
        return ['fecha_recepcion' => 'datetime'];
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'id_equipo', 'id_equipo');
    }

    public function recepcionista(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_recepcionista');
    }

    public function evidencias(): HasMany
    {
        return $this->hasMany(EvidenciaIngreso::class, 'id_orden', 'id_orden');
    }

    public function diagnosticos(): HasMany
    {
        return $this->hasMany(Diagnostico::class, 'id_orden', 'id_orden');
    }

    public function cotizacion(): HasOne
    {
        return $this->hasOne(Cotizacion::class, 'id_orden', 'id_orden');
    }

    public function reparacion(): HasOne
    {
        return $this->hasOne(Reparacion::class, 'id_orden', 'id_orden');
    }

    public function historialEstados(): HasMany
    {
        return $this->hasMany(HistorialEstadoOrden::class, 'id_orden', 'id_orden');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_orden', 'id_orden');
    }

    public function garantia(): HasOne
    {
        return $this->hasOne(Garantia::class, 'id_orden', 'id_orden');
    }
}
