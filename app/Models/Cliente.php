<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'nombre', 'apellido', 'telefono', 'correo_contacto',
        'direccion', 'fecha_registro', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_registro' => 'date',
            'estado' => 'boolean',
        ];
    }

    public function usuario(): HasOne
    {
        return $this->hasOne(User::class, 'id_cliente', 'id_cliente');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'id_cliente', 'id_cliente');
    }

    public function ordenesServicio(): HasManyThrough
    {
        return $this->hasManyThrough(OrdenServicio::class, Equipo::class, 'id_cliente', 'id_equipo', 'id_cliente', 'id_equipo');
    }

    public function aprobaciones(): HasMany
    {
        return $this->hasMany(Aprobacion::class, 'id_cliente', 'id_cliente');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_cliente', 'id_cliente');
    }

    public function garantias(): HasMany
    {
        return $this->hasMany(Garantia::class, 'id_cliente', 'id_cliente');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }
}
