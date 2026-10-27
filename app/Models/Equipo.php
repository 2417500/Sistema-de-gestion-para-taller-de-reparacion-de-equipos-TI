<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipo extends Model
{
    protected $table = 'equipos';

    protected $primaryKey = 'id_equipo';

    protected $fillable = [
        'id_cliente', 'id_tipo_equipo', 'marca', 'modelo',
        'numero_serie', 'caracteristicas', 'estado',
    ];

    protected function casts(): array
    {
        return ['estado' => 'boolean'];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function tipoEquipo(): BelongsTo
    {
        return $this->belongsTo(TipoEquipo::class, 'id_tipo_equipo', 'id_tipo_equipo');
    }

    public function ordenesServicio(): HasMany
    {
        return $this->hasMany(OrdenServicio::class, 'id_equipo', 'id_equipo');
    }
}
