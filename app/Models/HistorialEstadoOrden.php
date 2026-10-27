<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialEstadoOrden extends Model
{
    protected $table = 'historial_estados_orden';

    protected $primaryKey = 'id_historial';

    protected $fillable = [
        'id_orden', 'id_usuario', 'estado_anterior', 'estado_nuevo', 'comentario', 'fecha_cambio',
    ];

    protected function casts(): array
    {
        return ['fecha_cambio' => 'datetime'];
    }

    public function orden(): BelongsTo
    {
        return $this->belongsTo(OrdenServicio::class, 'id_orden', 'id_orden');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
