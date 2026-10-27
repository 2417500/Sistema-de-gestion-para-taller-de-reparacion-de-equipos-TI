<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    protected $table = 'roles';

    protected $primaryKey = 'id_rol';

    protected $fillable = ['nombre_rol', 'descripcion', 'estado'];

    protected function casts(): array
    {
        return ['estado' => 'boolean'];
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'id_rol', 'id_rol');
    }
}
