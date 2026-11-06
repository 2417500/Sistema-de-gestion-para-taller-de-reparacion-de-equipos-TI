<?php

namespace App\Services;

use App\Models\Refaccion;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;
use RuntimeException;

class InventarioService
{
    /**
     * Verifica si existe stock suficiente de una refacción en almacén.
     */
    public function verificarDisponibilidad(int $refaccionId, int $cantidad): bool
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException("La cantidad solicitada debe ser mayor a 0.");
        }

        $refaccion = Refaccion::findOrFail($refaccionId);
        return $refaccion->stock_disponible >= $cantidad;
    }

    /**
     * Despacha una refacción reduciendo el inventario disponible.
     */
    public function despacharRefaccion(int $refaccionId, int $cantidad): Refaccion
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException("La cantidad a despachar debe ser mayor a cero.");
        }

        $refaccion = Refaccion::findOrFail($refaccionId);

        if ($refaccion->stock_disponible < $cantidad) {
            throw new RuntimeException(
                "Stock insuficiente para la refacción [{$refaccion->nombre}]. Disponible: {$refaccion->stock_disponible}, Requerido: {$cantidad}."
            );
        }

        $refaccion->decrement('stock_disponible', $cantidad);

        return $refaccion->fresh();
    }

    /**
     * Restituye o reabastece refacciones devueltas o ingresadas al almacén.
     */
    public function restituirRefaccion(int $refaccionId, int $cantidad): Refaccion
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException("La cantidad a restituir debe ser mayor a cero.");
        }

        $refaccion = Refaccion::findOrFail($refaccionId);
        $refaccion->increment('stock_disponible', $cantidad);

        return $refaccion->fresh();
    }

    /**
     * Actualiza el stock directamente (por inventario físico o auditoría).
     */
    public function actualizarStock(int $refaccionId, int $nuevoStock): Refaccion
    {
        if ($nuevoStock < 0) {
            throw new InvalidArgumentException("El stock no puede ser un valor negativo.");
        }

        $refaccion = Refaccion::findOrFail($refaccionId);
        $refaccion->update(['stock_disponible' => $nuevoStock]);

        return $refaccion->fresh();
    }

    /**
     * Obtiene refacciones que se encuentran en o por debajo del umbral de stock crítico.
     */
    public function obtenerRefaccionesConStockBajo(int $umbral = 5): Collection
    {
        return Refaccion::where('stock_disponible', '<=', $umbral)->get();
    }
}

