<?php

use App\Models\Entrega;
use App\Models\Garantia;
use App\Models\Pago;
use App\Models\Reparacion;
use App\Services\PagoService;
use Illuminate\Validation\ValidationException;

// ──────────────────────────────────────────────────────────────────────────────
// PagoService – Anticipo, saldo, entrega y garantía
// ──────────────────────────────────────────────────────────────────────────────

describe('PagoService', function () {

    beforeEach(function () {
        $roles = crearRoles();
        $datos = crearClienteConUsuario($roles['cliente']);

        $this->cliente   = $datos['cliente'];
        $this->recepcion = crearUsuarioEmpleado($roles['recepcion'], 'rec@taller.com');
        $this->tecnico   = crearUsuarioEmpleado($roles['tecnico'],   'tec@taller.com');

        $this->equipo  = crearEquipo($this->cliente->id_cliente);
        $this->orden   = crearOrden($this->equipo->id_equipo, $this->recepcion->id);
        $this->service = app(PagoService::class);
    });

    it('registra un pago y crea el movimiento de caja', function () {
        $pago = $this->service->registrarPago(
            $this->orden, $this->cliente->id_cliente, $this->recepcion->id,
            500.0, 'Efectivo', null, 'Anticipo inicial',
        );

        expect($pago)->toBeInstanceOf(Pago::class)
            ->and((float) $pago->monto)->toEqual(500.0)
            ->and($pago->metodo_pago)->toBe('Efectivo')
            ->and($pago->movimientosCaja()->exists())->toBeTrue();
    });

    it('calcula el saldo pendiente correctamente', function () {
        $cotizacion = crearCotizacionAprobada($this->orden, 1000.0);
        crearAnticipo($this->orden, $this->cliente->id_cliente, $this->recepcion->id, 600.0);

        $saldo = $this->service->calcularSaldoPendiente($this->orden->fresh());

        expect((float) $saldo)->toBe(400.0);
    });

    it('saldo es 0 cuando se paga el total', function () {
        crearCotizacionAprobada($this->orden, 1000.0);
        crearAnticipo($this->orden, $this->cliente->id_cliente, $this->recepcion->id, 1000.0);

        $saldo = $this->service->calcularSaldoPendiente($this->orden->fresh());

        expect((float) $saldo)->toBe(0.0);
    });

    it('valida anticipo y lanza excepción si no se ha pagado el mínimo', function () {
        crearCotizacionAprobada($this->orden, 1000.0);
        // Sin pago
        expect(fn () => $this->service->validarAnticipo($this->orden->fresh()))
            ->toThrow(ValidationException::class);
    });

    it('valida anticipo correctamente cuando se ha cubierto el 50%', function () {
        crearCotizacionAprobada($this->orden, 1000.0);
        crearAnticipo($this->orden, $this->cliente->id_cliente, $this->recepcion->id, 500.0);

        expect(fn () => $this->service->validarAnticipo($this->orden->fresh()))->not->toThrow(ValidationException::class);
    });

    it('no entrega el equipo si hay saldo pendiente', function () {
        crearCotizacionAprobada($this->orden, 1000.0);
        crearAnticipo($this->orden, $this->cliente->id_cliente, $this->recepcion->id, 200.0);

        expect(fn () => $this->service->registrarEntrega(
            $this->orden->fresh(), $this->recepcion->id, 'Juan Receptor'
        ))->toThrow(ValidationException::class);
    });

    it('no entrega el equipo si no hay prueba final registrada', function () {
        crearCotizacionAprobada($this->orden, 1000.0);
        crearAnticipo($this->orden, $this->cliente->id_cliente, $this->recepcion->id, 1000.0);
        // Sin reparación ni prueba

        expect(fn () => $this->service->registrarEntrega(
            $this->orden->fresh(), $this->recepcion->id, 'Juan Receptor'
        ))->toThrow(ValidationException::class);
    });

    it('registra entrega correctamente con saldo cero y prueba final', function () {
        crearCotizacionAprobada($this->orden, 1000.0);
        crearAnticipo($this->orden, $this->cliente->id_cliente, $this->recepcion->id, 1000.0);

        // Crear reparación con prueba final
        $reparacion = Reparacion::create([
            'id_orden'   => $this->orden->id_orden,
            'id_tecnico' => $this->tecnico->id,
            'fecha_inicio' => now(),
            'estado'     => 'Terminada',
        ]);
        \App\Models\PruebaFinal::create([
            'id_reparacion'   => $reparacion->id_reparacion,
            'id_tecnico'      => $this->tecnico->id,
            'equipo_reparado' => true,
            'descripcion_prueba' => 'OK',
            'fecha_prueba'    => now(),
        ]);

        $entrega = $this->service->registrarEntrega(
            $this->orden->fresh(), $this->recepcion->id, 'Juan Receptor', 'INE-12345'
        );

        expect($entrega)->toBeInstanceOf(Entrega::class)
            ->and($entrega->nombre_receptor)->toBe('Juan Receptor')
            ->and($this->orden->fresh()->estado_orden)->toBe('Entregada');
    });

    it('emite garantía tras la entrega', function () {
        $garantia = $this->service->emitirGarantia(
            $this->orden,
            $this->recepcion->id,
            now()->addMonths(3)->toDateTime(),
            'Cubre mano de obra y refacciones instaladas',
            'Pantalla y batería',
            '90 días',
        );

        expect($garantia)->toBeInstanceOf(Garantia::class)
            ->and($garantia->estado)->toBe('Vigente');
        expect(str_contains($garantia->conceptos_cubiertos, 'Pantalla'))->toBeTrue();
    });
});
