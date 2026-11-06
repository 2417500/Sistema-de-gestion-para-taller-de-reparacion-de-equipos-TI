<?php

use App\Models\Refaccion;
use App\Services\InventarioService;

beforeEach(function () {
    $this->service = new InventarioService();

    $this->refaccion = Refaccion::create([
        'nombre' => 'Pasta Térmica Thermal Grizzly',
        'marca' => 'Kryonaut',
        'modelo' => 'TG-K-001',
        'precio_unitario' => 250.00,
        'stock_disponible' => 8,
    ]);
});

test('verificar disponibilidad y despachar refaccion reduce stock', function () {
    $disponible = $this->service->verificarDisponibilidad($this->refaccion->id, 3);
    expect($disponible)->toBeTrue();

    $refaccionDespachada = $this->service->despacharRefaccion($this->refaccion->id, 3);
    expect($refaccionDespachada->stock_disponible)->toBe(5);
});

test('despachar mas del stock disponible lanza excepcion', function () {
    expect(fn () => $this->service->despacharRefaccion($this->refaccion->id, 20))
        ->toThrow(RuntimeException::class);
});

test('restituir refaccion incrementa stock disponible', function () {
    $restituida = $this->service->restituirRefaccion($this->refaccion->id, 4);
    expect($restituida->stock_disponible)->toBe(12);
});

test('obtener refacciones con stock bajo segun umbral critico', function () {
    Refaccion::create([
        'nombre' => 'Batería HP HT03XL',
        'marca' => 'HP Original',
        'modelo' => 'HT03XL',
        'precio_unitario' => 1100.00,
        'stock_disponible' => 2, // Stock crítico
    ]);

    $bajoStock = $this->service->obtenerRefaccionesConStockBajo(5);
    expect($bajoStock)->toHaveCount(1)
        ->and($bajoStock->first()->modelo)->toBe('HT03XL');
});
