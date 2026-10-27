<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefaccionUtilizada extends Model
{
    protected $table = 'refacciones_utilizadas';

    protected $primaryKey = 'id_refaccion_utilizada';

    protected $fillable = ['id_reparacion', 'id_refaccion', 'cantidad', 'precio_unitario', 'importe'];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'precio_unitario' => 'decimal:2',
            'importe' => 'decimal:2',
        ];
    }

    public function reparacion(): BelongsTo
    {
        return $this->belongsTo(Reparacion::class, 'id_reparacion', 'id_reparacion');
    }

    public function refaccion(): BelongsTo
    {
        return $this->belongsTo(Refaccion::class, 'id_refaccion', 'id_refaccion');
    }
}
