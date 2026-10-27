<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $primaryKey = 'id_notificacion';

    protected $fillable = [
        'id_usuario', 'titulo', 'mensaje', 'tipo', 'enlace', 'leida', 'fecha_lectura',
    ];

    protected function casts(): array
    {
        return [
            'leida' => 'boolean',
            'fecha_lectura' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
