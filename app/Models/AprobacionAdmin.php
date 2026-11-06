<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AprobacionAdmin extends Model
{
    use HasFactory;

    protected $table = 'aprobacion_admins';

    protected $fillable = [
        'admin_id',
        'orden_servicio_id',
        'tipo_solicitud',
        'estado',
        'motivo',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function ordenServicio()
    {
        return $this->belongsTo(OrdenServicio::class, 'orden_servicio_id');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'Pendiente');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'Aprobado');
    }
}
