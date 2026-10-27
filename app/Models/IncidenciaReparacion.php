<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidenciaReparacion extends Model
{
    protected $table = 'incidencias_reparacion';
    protected $primaryKey = 'id_incidencia';

    protected $fillable = [
        'id_reparacion', 'id_tecnico', 'tipo', 'descripcion', 'acciones_tomadas',
        'estado', 'requiere_aprobacion_cliente', 'fecha_resolucion',
    ];

    protected function casts(): array
    {
        return [
            'requiere_aprobacion_cliente' => 'boolean',
            'fecha_resolucion' => 'datetime',
        ];
    }

    public function reparacion(): BelongsTo
    {
        return $this->belongsTo(Reparacion::class, 'id_reparacion', 'id_reparacion');
    }

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_tecnico');
    }
}
