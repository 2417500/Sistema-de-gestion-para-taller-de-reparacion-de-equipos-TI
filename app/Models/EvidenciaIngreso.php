<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvidenciaIngreso extends Model
{
    use HasFactory;

    protected $table = 'evidencia_ingresos';

    protected $fillable = [
        'orden_servicio_id',
        'ruta_imagen',
        'descripcion_estado',
    ];

    public function ordenServicio()
    {
        return $this->belongsTo(OrdenServicio::class, 'orden_servicio_id');
    }
}