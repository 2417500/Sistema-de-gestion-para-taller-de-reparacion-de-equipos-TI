<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'equipos_reparados_count',
        'aplica_descuento',
    ];

    protected function casts(): array
    {
        return [
            'equipos_reparados_count' => 'integer',
            'aplica_descuento' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'cliente_id');
    }

    public function ordenesServicio()
    {
        return $this->hasManyThrough(
            OrdenServicio::class,
            Equipo::class,
            'cliente_id', // Foreign key en equipos
            'equipo_id',  // Foreign key en orden_servicios
            'id',         // Local key en clientes
            'id'          // Local key en equipos
        );
    }

    /**
     * Actualiza el estado de descuento del 15% (aplica al superar 5 equipos reparados).
     */
    public function actualizarElegibilidadDescuento(): bool
    {
        $this->aplica_descuento = ($this->equipos_reparados_count > 5);
        $this->save();

        return $this->aplica_descuento;
    }
}
