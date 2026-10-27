<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservaRefaccion extends Model
{
    protected $table = 'reservas_refacciones';
    protected $primaryKey = 'id_reserva';

    protected $fillable = ['id_orden', 'id_refaccion', 'cantidad', 'estado'];

    public function orden(): BelongsTo
    {
        return $this->belongsTo(OrdenServicio::class, 'id_orden', 'id_orden');
    }

    public function refaccion(): BelongsTo
    {
        return $this->belongsTo(Refaccion::class, 'id_refaccion', 'id_refaccion');
    }
}
