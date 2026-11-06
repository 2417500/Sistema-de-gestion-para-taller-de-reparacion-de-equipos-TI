<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BitacoraAccion extends Model
{
    protected $table = 'bitacora_acciones';
    protected $primaryKey = 'id_bitacora';

    protected $fillable = [
        'id_usuario', 'accion', 'modulo', 'id_referencia', 'detalle', 'ip',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
