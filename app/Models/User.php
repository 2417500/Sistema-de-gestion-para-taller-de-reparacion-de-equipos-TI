<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'id_rol', 'id_cliente'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function empleado(): HasOne
    {
        return $this->hasOne(Empleado::class, 'user_id');
    }

    public function ordenesRecibidas(): HasMany
    {
        return $this->hasMany(OrdenServicio::class, 'id_recepcionista');
    }

    public function diagnosticos(): HasMany
    {
        return $this->hasMany(Diagnostico::class, 'id_tecnico');
    }

    public function reparaciones(): HasMany
    {
        return $this->hasMany(Reparacion::class, 'id_tecnico');
    }

    public function notificacionesSistema(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'id_usuario');
    }
}
