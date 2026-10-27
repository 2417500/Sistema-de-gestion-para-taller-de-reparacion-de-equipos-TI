<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionTaller extends Model
{
    protected $table = 'configuracion_taller';

    protected $primaryKey = 'id_configuracion';

    protected $fillable = [
        'nombre_comercial', 'razon_social', 'telefono', 'correo_contacto',
        'direccion', 'logo', 'moneda', 'terminos_condiciones', 'estado',
    ];

    protected function casts(): array
    {
        return ['estado' => 'boolean'];
    }
}
