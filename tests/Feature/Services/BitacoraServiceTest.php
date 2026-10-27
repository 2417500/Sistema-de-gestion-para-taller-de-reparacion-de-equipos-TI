<?php

use App\Models\BitacoraAccion;
use App\Services\BitacoraService;
use Illuminate\Support\Facades\Auth;

// ──────────────────────────────────────────────────────────────────────────────
// BitacoraService – Auditoría de acciones del sistema
// ──────────────────────────────────────────────────────────────────────────────

describe('BitacoraService', function () {

    beforeEach(function () {
        $this->roles   = crearRoles();
        $this->service = app(BitacoraService::class);
    });

    it('registra una acción con todos los campos', function () {
        $datos = crearClienteConUsuario($this->roles['cliente']);
        Auth::login($datos['user']);

        $bitacora = $this->service->registrar(
            accion: 'test_accion',
            modulo: 'OrdenServicio',
            idReferencia: 99,
            detalle: 'Detalle de prueba',
        );

        expect($bitacora)->toBeInstanceOf(BitacoraAccion::class)
            ->and($bitacora->accion)->toBe('test_accion')
            ->and($bitacora->modulo)->toBe('OrdenServicio')
            ->and($bitacora->id_referencia)->toBe(99)
            ->and($bitacora->detalle)->toBe('Detalle de prueba')
            ->and($bitacora->id_usuario)->toBe($datos['user']->id);
    });

    it('registra detalle como JSON cuando se pasa un array', function () {
        $datos = crearClienteConUsuario($this->roles['cliente']);

        $bitacora = $this->service->registrar(
            accion: 'pago_registrado',
            modulo: 'Pago',
            idReferencia: 1,
            detalle: ['monto' => 500, 'metodo' => 'Efectivo'],
            idUsuario: $datos['user']->id,
        );

        expect(str_contains($bitacora->detalle, '"monto"'))->toBeTrue();
    });

    it('registra cambio de estado de orden correctamente', function () {
        $datos = crearClienteConUsuario($this->roles['cliente']);

        $this->service->cambioEstadoOrden(1, 'Recibida', 'En Diagnóstico', $datos['user']->id);

        $registro = BitacoraAccion::where('accion', 'cambio_estado')->first();
        expect($registro)->not->toBeNull();
        expect(str_contains($registro->detalle, 'Recibida'))->toBeTrue();
        expect(str_contains($registro->detalle, 'En Diagnóstico'))->toBeTrue();
    });

    it('registra un pago con monto y método', function () {
        $datos = crearClienteConUsuario($this->roles['cliente']);
        Auth::login($datos['user']);

        $this->service->pagoRegistrado(10, '500.00', 'Transferencia');

        $registro = BitacoraAccion::where('accion', 'pago_registrado')->first();
        expect($registro)->not->toBeNull();
        expect(str_contains($registro->detalle, 'Transferencia'))->toBeTrue();
    });

    it('registra cancelación de orden con motivo', function () {
        $datos = crearClienteConUsuario($this->roles['cliente']);
        Auth::login($datos['user']);

        $this->service->cancelacionOrden(5, 'Cliente no se presentó');

        $registro = BitacoraAccion::where('accion', 'cancelacion_orden')->first();
        expect($registro)->not->toBeNull()
            ->and($registro->detalle)->toBe('Cliente no se presentó');
    });
});
