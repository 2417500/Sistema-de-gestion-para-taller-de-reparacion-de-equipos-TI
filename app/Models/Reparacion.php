<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reparacion extends Model
{
    protected $table = 'reparaciones';

    protected $primaryKey = 'id_reparacion';

    protected $fillable = [
        'id_orden', 'id_tecnico', 'fecha_inicio', 'fecha_fin',
        'trabajo_realizado', 'observaciones', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
        ];
    }

    public function orden(): BelongsTo
    {
        return $this->belongsTo(OrdenServicio::class, 'id_orden', 'id_orden');
    }

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_tecnico');
    }

    public function refaccionesUtilizadas(): HasMany
    {
        return $this->hasMany(RefaccionUtilizada::class, 'id_reparacion', 'id_reparacion');
    }
}
