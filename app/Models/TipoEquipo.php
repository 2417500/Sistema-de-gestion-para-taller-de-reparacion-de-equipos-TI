<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoEquipo extends Model
{
    protected $table = 'tipos_equipo';

    protected $primaryKey = 'id_tipo_equipo';

    protected $fillable = ['nombre_tipo', 'descripcion', 'estado'];

    protected function casts(): array
    {
        return ['estado' => 'boolean'];
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'id_tipo_equipo', 'id_tipo_equipo');
    }
}
