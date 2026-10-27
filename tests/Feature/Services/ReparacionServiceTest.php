<?php

use App\Models\IncidenciaReparacion;
use App\Models\Notificacion;
use App\Models\PruebaFinal;
use App\Models\Reparacion;
use App\Services\ReparacionService;
use Illuminate\Validation\ValidationException;

// ──────────────────────────────────────────────────────────────────────────────
// ReparacionService – Ciclo de vida de la reparación
// ──────────────────────────────────────────────────────────────────────────────

describe('ReparacionService', function () {

    beforeEach(function () {
        $roles = crearRoles();
        $datos = crearClienteConUsuario($roles['cliente']);

        $this->cliente   = $datos['cliente'];
        $this->recepcion = crearUsuarioEmpleado($roles['recepcion'], 'rec@taller.com');
        $this->tecnico   = crearUsuarioEmpleado($roles['tecnico'],   'tec@taller.com');

        $this->equipo  = crearEquipo($this->cliente->id_cliente);
        $this->orden   = crearOrden($this->equipo->id_equipo, $this->recepcion->id);
        $this->service = app(ReparacionService::class);
    });

    it('no puede iniciar sin cotización aceptada', function () {
        expect(fn () => $this->service->iniciar($this->orden, $this->tecnico->id))
            ->toThrow(ValidationException::class);
    });

    it('no puede iniciar sin anticipo pagado', function () {
        // Cotización aprobada pero sin pago
        crearCotizacionAprobada($this->orden, 1000.0);

        expect(fn () => $this->service->iniciar($this->orden->fresh(), $this->tecnico->id))
            ->toThrow(ValidationException::class);
    });

    it('inicia reparación correctamente con cotización aprobada y anticipo', function () {
        $cotizacion = crearCotizacionAprobada($this->orden, 1000.0);
        crearAnticipo($this->orden, $this->cliente->id_cliente, $this->recepcion->id, 500.0);

        $reparacion = $this->service->iniciar($this->orden->fresh(), $this->tecnico->id);

        expect($reparacion)->toBeInstanceOf(Reparacion::class)
            ->and($reparacion->estado)->toBe('En Proceso')
            ->and($this->orden->fresh()->estado_orden)->toBe('En Reparación');
    });

    it('actualiza el trabajo realizado por el técnico', function () {
        crearCotizacionAprobada($this->orden, 1000.0);
        crearAnticipo($this->orden, $this->cliente->id_cliente, $this->recepcion->id, 500.0);

        $reparacion = $this->service->iniciar($this->orden->fresh(), $this->tecnico->id);
        $this->service->actualizarTrabajo($reparacion, ['trabajo_realizado' => 'Cambio de RAM completado']);

        expect($reparacion->fresh()->trabajo_realizado)->toBe('Cambio de RAM completado');
    });

    it('registra una incidencia durante la reparación', function () {
        crearCotizacionAprobada($this->orden, 1000.0);
        crearAnticipo($this->orden, $this->cliente->id_cliente, $this->recepcion->id, 500.0);

        $reparacion = $this->service->iniciar($this->orden->fresh(), $this->tecnico->id);

        $incidencia = $this->service->registrarIncidencia(
            $reparacion, $this->tecnico->id, 'falla_adicional', 'Se detectó daño en la placa base'
        );

        expect($incidencia)->toBeInstanceOf(IncidenciaReparacion::class)
            ->and($incidencia->tipo)->toBe('falla_adicional');
    });

    it('incidencia que requiere aprobación del cliente genera notificación', function () {
        crearCotizacionAprobada($this->orden, 1000.0);
        crearAnticipo($this->orden, $this->cliente->id_cliente, $this->recepcion->id, 500.0);

        $reparacion = $this->service->iniciar($this->orden->fresh(), $this->tecnico->id);

        $this->service->registrarIncidencia(
            $reparacion, $this->tecnico->id, 'falla_adicional', 'Necesita pieza extra', true
        );

        expect(Notificacion::where('tipo', 'Incidencia')->exists())->toBeTrue();
    });

    it('registra prueba final y marca reparación como Terminada', function () {
        crearCotizacionAprobada($this->orden, 1000.0);
        crearAnticipo($this->orden, $this->cliente->id_cliente, $this->recepcion->id, 500.0);

        $reparacion = $this->service->iniciar($this->orden->fresh(), $this->tecnico->id);

        $prueba = $this->service->registrarPruebaFinal(
            $reparacion, $this->tecnico->id, true, 'Encendido y funcional', 'Equipo OK'
        );

        expect($prueba)->toBeInstanceOf(PruebaFinal::class)
            ->and($reparacion->fresh()->estado)->toBe('Terminada')
            ->and($this->orden->fresh()->estado_orden)->toBe('Lista');
    });

    it('prueba fallida no cambia estado de la reparación a Terminada', function () {
        crearCotizacionAprobada($this->orden, 1000.0);
        crearAnticipo($this->orden, $this->cliente->id_cliente, $this->recepcion->id, 500.0);

        $reparacion = $this->service->iniciar($this->orden->fresh(), $this->tecnico->id);

        $this->service->registrarPruebaFinal(
            $reparacion, $this->tecnico->id, false, 'No enciende', null, 'Placa dañada'
        );

        expect($reparacion->fresh()->estado)->not->toBe('Terminada');
    });
});
