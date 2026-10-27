<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diagnostico extends Model
{
    protected $table = 'diagnosticos';

    protected $primaryKey = 'id_diagnostico';

    protected $fillable = [
        'id_orden', 'id_tecnico', 'fecha_diagnostico', 'falla_detectada', 'causa_probable',
        'solucion_recomendada', 'requiere_refacciones', 'requiere_reparacion', 'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_diagnostico' => 'datetime',
            'requiere_refacciones' => 'boolean',
            'requiere_reparacion' => 'boolean',
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
}
