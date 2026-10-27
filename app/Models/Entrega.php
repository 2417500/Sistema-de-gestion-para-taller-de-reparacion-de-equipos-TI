<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrega extends Model
{
    protected $table = 'entregas';
    protected $primaryKey = 'id_entrega';

    protected $fillable = [
        'id_orden', 'id_responsable_entrega', 'nombre_receptor',
        'identificacion_receptor', 'fecha_entrega', 'folio_comprobante', 'observaciones',
    ];

    protected function casts(): array
    {
        return ['fecha_entrega' => 'datetime'];
    }

    public function orden(): BelongsTo
    {
        return $this->belongsTo(OrdenServicio::class, 'id_orden', 'id_orden');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_responsable_entrega');
    }
}
