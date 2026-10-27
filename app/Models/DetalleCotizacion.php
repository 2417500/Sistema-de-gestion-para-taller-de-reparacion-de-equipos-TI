<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleCotizacion extends Model
{
    protected $table = 'detalle_cotizacion';

    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_version', 'id_refaccion', 'tipo_concepto', 'descripcion',
        'cantidad', 'precio_unitario', 'importe',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'decimal:2',
            'precio_unitario' => 'decimal:2',
            'importe' => 'decimal:2',
        ];
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(VersionCotizacion::class, 'id_version', 'id_version');
    }

    public function refaccion(): BelongsTo
    {
        return $this->belongsTo(Refaccion::class, 'id_refaccion', 'id_refaccion');
    }
}
