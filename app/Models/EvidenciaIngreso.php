<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvidenciaIngreso extends Model
{
    protected $table = 'evidencias_ingreso';

    protected $primaryKey = 'id_evidencia';

    protected $fillable = [
        'id_orden', 'ruta_archivo', 'tipo_archivo', 'descripcion', 'id_usuario_registro',
    ];

    public function orden(): BelongsTo
    {
        return $this->belongsTo(OrdenServicio::class, 'id_orden', 'id_orden');
    }

    public function usuarioRegistro(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_registro');
    }
}
