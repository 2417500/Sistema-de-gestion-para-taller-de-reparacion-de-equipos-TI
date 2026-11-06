<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aprobacion extends Model
{
    protected $table = 'aprobaciones';

    protected $primaryKey = 'id_aprobacion';

    protected $fillable = ['id_version', 'id_cliente', 'estado', 'fecha_respuesta', 'comentarios', 'motivo_rechazo'];

    protected function casts(): array
    {
        return ['fecha_respuesta' => 'datetime'];
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(VersionCotizacion::class, 'id_version', 'id_version');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }
}
