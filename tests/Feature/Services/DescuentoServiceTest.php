<?php

use App\Models\ReglaDescuento;
use App\Services\DescuentoService;

// ──────────────────────────────────────────────────────────────────────────────
// DescuentoService – Cálculo de descuentos por fidelidad y antigüedad
// ──────────────────────────────────────────────────────────────────────────────

describe('DescuentoService', function () {

    beforeEach(function () {
        $this->roles   = crearRoles();
        $this->service = app(DescuentoService::class);
    });

    it('sin historial y sin antigüedad retorna 0% de descuento', function () {
        $datos = crearClienteConUsuario($this->roles['cliente']);
        $resultado = $this->service->evaluar($datos['cliente']->id_cliente);

        expect($resultado['porcentaje'])->toBe(0.0);
    });

    it('aplica descuento por antigüedad si el cliente tiene 1+ año', function () {
        // Crear regla de antigüedad del 10%
        ReglaDescuento::create([
            'nombre'               => 'Antigüedad 1 año',
            'descripcion'          => 'Por antigüedad mínima de 1 año',
            'porcentaje_descuento' => 10,
            // reparaciones_requeridas usa DEFAULT 5; no bloquea la evaluación por antigüedad
            'estado'               => true,
        ]);

        // Cliente con exactamente 1 año → 10%
        $datos = crearClienteConUsuario($this->roles['cliente'], [
            'correo_contacto' => 'antiguo@mail.com',
            'fecha_registro'  => now()->subYear()->subDay(),
        ]);

        $resultado = $this->service->evaluar($datos['cliente']->id_cliente);

        expect($resultado['porcentaje'])->toBe(10.0);
    });

    it('aplica descuento de fidelidad con 5 reparaciones pagadas', function () {
        ReglaDescuento::create([
            'nombre'                  => 'Fidelidad 5 reparaciones',
            'descripcion'             => 'Por 5 equipos reparados y pagados',
            'porcentaje_descuento'    => 5,
            'reparaciones_requeridas' => 5,
            'estado'                  => true,
        ]);

        $recepcion = crearUsuarioEmpleado($this->roles['recepcion'], 'rec@taller.com');
        $datos     = crearClienteConUsuario($this->roles['cliente']);
        $cliente   = $datos['cliente'];

        // Simular 5 órdenes entregadas con pago registrado
        for ($i = 0; $i < 5; $i++) {
            $equipo = crearEquipo($cliente->id_cliente, ['numero_serie' => 'SN-' . $i]);
            $orden  = crearOrden($equipo->id_equipo, $recepcion->id, ['estado_orden' => 'Entregada']);
            crearAnticipo($orden, $cliente->id_cliente, $recepcion->id, 500.0);
        }

        $resultado = $this->service->evaluar($cliente->id_cliente);

        expect($resultado['porcentaje'])->toBe(5.0);
    });

    it('calcula monto de descuento sobre el subtotal', function () {
        ReglaDescuento::create([
            'nombre'               => 'Antigüedad 10%',
            'descripcion'          => 'Por 1 año de antigüedad',
            'porcentaje_descuento' => 10,
            'estado'               => true,
        ]);

        // Cliente con 1 año exacto → ANTIGUEDAD_BASE = 10%
        $datos = crearClienteConUsuario($this->roles['cliente'], [
            'correo_contacto' => 'viejo@mail.com',
            'fecha_registro'  => now()->subYear()->subDay(),
        ]);

        [$subtotal, $descuento, $porcentaje, $regla] = $this->service->calcularParaCotizacion(
            $datos['cliente']->id_cliente,
            1000.0,
        );

        expect($subtotal)->toBe(1000.0)
            ->and($descuento)->toBe(100.0)
            ->and($porcentaje)->toBeGreaterThanOrEqual(10.0);
    });

    it('selecciona el descuento más favorable cuando hay varios aplicables', function () {
        ReglaDescuento::create([
            'nombre' => 'Antigüedad 10%', 'descripcion' => 'Por antigüedad',
            'porcentaje_descuento' => 10, 'estado' => true,
        ]);
        ReglaDescuento::create([
            'nombre' => 'Fidelidad 5%', 'descripcion' => 'Por 5 reparaciones',
            'porcentaje_descuento' => 5, 'reparaciones_requeridas' => 5, 'estado' => true,
        ]);

        // Con 2 años de antigüedad → porcentaje = 15% (ANTIGUEDAD_MAX)
        // Con 5 reparaciones → porcentaje por fidelidad = 5%
        // El service elige el MAYOR → 15%
        $recepcion = crearUsuarioEmpleado($this->roles['recepcion'], 'rec2@taller.com');
        $datos = crearClienteConUsuario($this->roles['cliente'], [
            'correo_contacto' => 'doble@mail.com',
            'fecha_registro'  => now()->subYears(2)->subDay(),
        ]);
        $cliente = $datos['cliente'];

        for ($i = 0; $i < 5; $i++) {
            $eq = crearEquipo($cliente->id_cliente, ['numero_serie' => 'FID-' . $i]);
            $or = crearOrden($eq->id_equipo, $recepcion->id, ['estado_orden' => 'Entregada']);
            crearAnticipo($or, $cliente->id_cliente, $recepcion->id, 200.0);
        }

        $resultado = $this->service->evaluar($cliente->id_cliente);

        // 15% (antigüedad) > 5% (fidelidad) → elige 15%
        expect($resultado['porcentaje'])->toBe(15.0);
    });
});
