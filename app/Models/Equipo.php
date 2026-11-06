<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'tipo_equipo',
        'marca',
        'modelo',
        'numero_serie',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function ordenesServicio()
    {
        return $this->hasMany(OrdenServicio::class, 'equipo_id');
    }

    public function scopePorNumeroSerie($query, string $numeroSerie)
    {
        return $query->where('numero_serie', $numeroSerie);
    }

    public function getDescripcionCompletaAttribute(): string
    {
        return "{$this->tipo_equipo} {$this->marca} {$this->modelo} (S/N: {$this->numero_serie})";
    }
}
