<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $primaryKey = 'id_empleado';

    protected $fillable = [
        'user_id', 'numero_empleado', 'puesto', 'fecha_contratacion', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_contratacion' => 'date',
            'estado' => 'boolean',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
