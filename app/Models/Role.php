<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function scopePorNombre($query, string $nombre)
    {
        return $query->where('nombre', $nombre);
    }
}
