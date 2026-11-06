<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenServicio extends Model
{
    use HasFactory;

    protected $table = 'orden_servicios';

    protected $fillable = [
        'codigo_servicio',
        'equipo_id',
        'recepcion_id',
        'tecnico_id',
        'tipo_servicio',
        'falla_reportada',
        'estado',
        'requiere_aprobacion_admin',
    ];

    protected function casts(): array
    {
        return [
            'requiere_aprobacion_admin' => 'boolean',
        ];
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function cliente()
    {
        return $this->hasOneThrough(
            Cliente::class,
            Equipo::class,
            'id',          // Local key en equipos
            'id',          // Local key en clientes
            'equipo_id',   // Foreign key en orden_servicios
            'cliente_id'   // Foreign key en equipos
        );
    }

    public function recepcion()
    {
        return $this->belongsTo(User::class, 'recepcion_id');
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function evidencias()
    {
        return $this->hasMany(EvidenciaIngreso::class, 'orden_servicio_id');
    }

    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class, 'orden_servicio_id');
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'orden_servicio_id');
    }

    public function cotizacionActual()
    {
        return $this->hasOne(Cotizacion::class, 'orden_servicio_id')->latestOfMany();
    }

    public function prestamosHerramientas()
    {
        return $this->hasMany(PrestamoHerramienta::class, 'orden_servicio_id');
    }

    public function aprobacionesAdmin()
    {
        return $this->hasMany(AprobacionAdmin::class, 'orden_servicio_id');
    }

    public function historiales()
    {
        return $this->hasMany(HistorialEstado::class, 'orden_servicio_id');
    }

    public function ticketPago()
    {
        return $this->hasOne(TicketPago::class, 'orden_servicio_id');
    }

    // Scopes de Estado
    public function scopePendientes($query)
    {
        return $query->where('estado', 'Pendiente');
    }

    public function scopeEnProceso($query)
    {
        return $query->where('estado', 'En_Proceso');
    }

    public function scopeTerminados($query)
    {
        return $query->where('estado', 'Terminado');
    }

    public function scopeEntregados($query)
    {
        return $query->where('estado', 'Entregado');
    }

    public function scopePorCodigo($query, string $codigo)
    {
        return $query->where('codigo_servicio', $codigo);
    }
}
