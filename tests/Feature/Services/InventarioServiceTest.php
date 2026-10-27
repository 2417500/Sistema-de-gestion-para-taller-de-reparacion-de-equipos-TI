<?php

use App\Models\MovimientoInventario;
use App\Models\ReservaRefaccion;
use App\Services\InventarioService;
use Illuminate\Validation\ValidationException;

// ──────────────────────────────────────────────────────────────────────────────
// InventarioService – Existencias, reservas y alertas de stock
// ──────────────────────────────────────────────────────────────────────────────

describe('InventarioService', function () {

    beforeEach(function () {
        $roles = crearRoles();
        $datos = crearClienteConUsuario($roles['cliente']);

        $this->cliente   = $datos['cliente'];
        $this->recepcion = crearUsuarioEmpleado($roles['recepcion'], 'rec@taller.com');
        $this->equipo    = crearEquipo($this->cliente->id_cliente);
        $this->orden     = crearOrden($this->equipo->id_equipo, $this->recepcion->id);
        $this->refaccion = crearRefaccion(10);
        $this->service   = app(InventarioService::class);
    });

    it('registra una entrada y aumenta existencia', function () {
        $antes = $this->refaccion->existencia;

        $this->service->registrarEntrada($this->refaccion->id_refaccion, $this->recepcion->id, 5, 80.0, 'Compra');

        expect($this->refaccion->fresh()->existencia)->toBe($antes + 5);
        expect(MovimientoInventario::where('tipo_movimiento', 'Entrada')->exists())->toBeTrue();
    });

    it('registra una salida y disminuye existencia', function () {
        $antes = $this->refaccion->existencia;

        $this->service->registrarSalida($this->refaccion->id_refaccion, $this->recepcion->id, 3, 'Baja');

        expect($this->refaccion->fresh()->existencia)->toBe($antes - 3);
    });

    it('lanza excepción si la salida supera la existencia', function () {
        expect(fn () =>
            $this->service->registrarSalida($this->refaccion->id_refaccion, $this->recepcion->id, 999, 'Exceso')
        )->toThrow(ValidationException::class);
    });

    it('reserva refacciones para una orden', function () {
        $this->service->reservar(
            $this->orden->id_orden,
            [$this->refaccion->id_refaccion => 3],
            $this->recepcion->id,
        );

        $reserva = ReservaRefaccion::where('id_orden', $this->orden->id_orden)->first();
        expect($reserva)->not->toBeNull()
            ->and($reserva->cantidad)->toBe(3)
            ->and($reserva->estado)->toBe('Reservada');
    });

    it('no permite reservar más de lo disponible', function () {
        expect(fn () =>
            $this->service->reservar(
                $this->orden->id_orden,
                [$this->refaccion->id_refaccion => 999],
                $this->recepcion->id,
            )
        )->toThrow(ValidationException::class);
    });

    it('libera reservas al cancelar una orden', function () {
        $this->service->reservar(
            $this->orden->id_orden,
            [$this->refaccion->id_refaccion => 2],
            $this->recepcion->id,
        );

        $this->service->liberarReservas($this->orden->id_orden, $this->recepcion->id);

        $reserva = ReservaRefaccion::where('id_orden', $this->orden->id_orden)->first();
        expect($reserva->estado)->toBe('Liberada');
    });

    it('entrega refacción para reparación y descuenta del inventario', function () {
        $existenciaAntes = $this->refaccion->existencia;

        $this->service->entregarParaReparacion(
            $this->orden->id_orden,
            $this->refaccion->id_refaccion,
            2,
        );

        expect($this->refaccion->fresh()->existencia)->toBe($existenciaAntes - 2);
    });

    it('no cuenta como doble descuento piezas ya reservadas al entregarlas', function () {
        $this->service->reservar(
            $this->orden->id_orden,
            [$this->refaccion->id_refaccion => 3],
            $this->recepcion->id,
        );

        $existenciaAntes = $this->refaccion->fresh()->existencia;

        $this->service->entregarParaReparacion($this->orden->id_orden, $this->refaccion->id_refaccion, 3);

        expect($this->refaccion->fresh()->existencia)->toBe($existenciaAntes - 3);
    });
});
