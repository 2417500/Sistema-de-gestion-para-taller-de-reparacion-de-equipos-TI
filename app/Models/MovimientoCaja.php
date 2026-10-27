<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoCaja extends Model
{
    protected $table = 'movimientos_caja';

    protected $primaryKey = 'id_movimiento';

    protected $fillable = [
        'id_pago', 'id_usuario', 'tipo_movimiento', 'monto',
        'concepto', 'fecha_movimiento', 'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'fecha_movimiento' => 'datetime',
        ];
    }

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class, 'id_pago', 'id_pago');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
