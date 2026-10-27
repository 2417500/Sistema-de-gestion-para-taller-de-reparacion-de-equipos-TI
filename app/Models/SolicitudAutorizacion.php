<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudAutorizacion extends Model
{
    protected $table = 'solicitudes_autorizacion';
    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'id_cliente', 'id_usuario_solicitante', 'id_usuario_autorizador',
        'tipo', 'descripcion', 'estado', 'motivo_rechazo', 'fecha_respuesta',
    ];

    protected function casts(): array
    {
        return ['fecha_respuesta' => 'datetime'];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_solicitante');
    }

    public function autorizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_autorizador');
    }
}
