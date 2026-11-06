<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'telefono',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'user_id');
    }

    public function ordenesAtendidas()
    {
        return $this->hasMany(OrdenServicio::class, 'recepcion_id');
    }

    public function ordenesAsignadas()
    {
        return $this->hasMany(OrdenServicio::class, 'tecnico_id');
    }

    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class, 'tecnico_id');
    }

    public function prestamosHerramientas()
    {
        return $this->hasMany(PrestamoHerramienta::class, 'tecnico_id');
    }

    public function aprobacionesAdmin()
    {
        return $this->hasMany(AprobacionAdmin::class, 'admin_id');
    }

    public function historiales()
    {
        return $this->hasMany(HistorialEstado::class, 'usuario_id');
    }

    /**
     * Comprueba si el usuario tiene un rol determinado o alguno de los roles provistos.
     */
    public function hasRole(string|array $roles): bool
    {
        if (!$this->role) {
            return false;
        }

        $rolesArray = is_array($roles) ? $roles : explode(',', $roles);
        $rolesNormalized = array_map(fn($r) => strtolower(trim($r)), $rolesArray);

        return in_array(strtolower($this->role->nombre), $rolesNormalized, true);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Administrador');
    }

    public function isRecepcion(): bool
    {
        return $this->hasRole('Recepcion');
    }

    public function isTecnico(): bool
    {
        return $this->hasRole('Tecnico');
    }

    public function isAlmacen(): bool
    {
        return $this->hasRole('Almacen');
    }

    public function isCliente(): bool
    {
        return $this->hasRole('Cliente');
    }
}
