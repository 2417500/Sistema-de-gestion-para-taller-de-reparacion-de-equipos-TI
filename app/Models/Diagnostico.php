<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    use HasFactory;

    protected $fillable = [
        'orden_servicio_id',
        'tecnico_id',
        'hallazgos',
        'solucion_propuesta',
        'costo_diagnostico',
    ];

    protected function casts(): array
    {
        return [
            'costo_diagnostico' => 'decimal:2',
        ];
    }

    public function ordenServicio()
    {
        return $this->belongsTo(OrdenServicio::class, 'orden_servicio_id');
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }
}
