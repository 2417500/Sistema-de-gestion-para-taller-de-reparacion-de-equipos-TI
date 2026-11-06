<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PruebaFinal extends Model
{
    protected $table = 'pruebas_finales';
    protected $primaryKey = 'id_prueba';

    protected $fillable = [
        'id_reparacion', 'id_tecnico', 'equipo_reparado',
        'descripcion_prueba', 'resultado', 'causa_no_reparado', 'fecha_prueba',
    ];

    protected function casts(): array
    {
        return [
            'equipo_reparado' => 'boolean',
            'fecha_prueba' => 'datetime',
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
