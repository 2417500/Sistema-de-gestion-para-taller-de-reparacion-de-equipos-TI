<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestamoHerramienta extends Model
{
    use HasFactory;

    protected $table = 'prestamo_herramientas';

    protected $fillable = [
        'orden_servicio_id',
        'herramienta_id',
        'tecnico_id',
        'cantidad_prestada',
        'estado_prestamo',
        'fecha_prestamo',
        'fecha_devolucion',
    ];

    protected function casts(): array
    {
        return [
            'cantidad_prestada' => 'integer',
            'fecha_prestamo' => 'datetime',
            'fecha_devolucion' => 'datetime',
        ];
    }

    public function ordenServicio()
    {
        return $this->belongsTo(OrdenServicio::class, 'orden_servicio_id');
    }

    public function herramienta()
    {
        return $this->belongsTo(Herramienta::class, 'herramienta_id');
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function scopeActivos($query)
    {
        return $query->whereIn('estado_prestamo', ['Solicitado', 'Aprobado_Admin', 'Entregado']);
    }

    public function scopeDevueltos($query)
    {
        return $query->where('estado_prestamo', 'Devuelto');
    }
}
