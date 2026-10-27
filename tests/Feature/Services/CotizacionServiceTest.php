<?php

use App\Models\Cotizacion;
use App\Models\VersionCotizacion;
use App\Services\CotizacionService;
use Illuminate\Validation\ValidationException;

// ──────────────────────────────────────────────────────────────────────────────
// CotizacionService – Versiones, aceptación y rechazo de cotizaciones
// ──────────────────────────────────────────────────────────────────────────────

describe('CotizacionService', function () {

    beforeEach(function () {
        $roles = crearRoles();
        $datos = crearClienteConUsuario($roles['cliente']);

        $this->cliente     = $datos['cliente'];
        $this->userCliente = $datos['user'];
        $this->recepcion   = crearUsuarioEmpleado($roles['recepcion'], 'rec@taller.com');
        $this->admin       = crearUsuarioEmpleado($roles['admin'], 'admin@taller.com');

        $this->equipo  = crearEquipo($this->cliente->id_cliente);
        $this->orden   = crearOrden($this->equipo->id_equipo, $this->recepcion->id);
        $this->service = app(CotizacionService::class);

        $this->detalles = [
            ['tipo_concepto' => 'Mano de obra', 'descripcion' => 'Diagnóstico', 'cantidad' => 1, 'precio_unitario' => 200.0],
            ['tipo_concepto' => 'Refacción',    'descripcion' => 'RAM 8GB',     'cantidad' => 1, 'precio_unitario' => 400.0],
        ];
    });

    it('crea la primera versión de cotización', function () {
        $version = $this->service->crearCotizacion($this->orden, $this->detalles, $this->recepcion->id);

        expect($version)->toBeInstanceOf(VersionCotizacion::class)
            ->and($version->numero_version)->toBe(1)
            ->and((float) $version->total)->toEqual(600.0)
            ->and($version->detalles()->count())->toBe(2);
    });

    it('una segunda versión incrementa el número de versión', function () {
        $v1 = $this->service->crearCotizacion($this->orden, $this->detalles, $this->recepcion->id);
        // Recargar la cotización desde la BD para tener la relación actualizada
        $cotizacion = \App\Models\Cotizacion::find($v1->id_cotizacion);
        $v2 = $this->service->nuevaVersion($cotizacion, $this->detalles, $this->recepcion->id);

        expect($v2->numero_version)->toBe(2)
            ->and($v1->id_version)->not->toBe($v2->id_version);
    });

    it('el cliente puede aceptar la cotización vigente', function () {
        $version = $this->service->crearCotizacion($this->orden, $this->detalles, $this->recepcion->id);

        $aprobacion = $this->service->aceptarPorCliente($version, $this->cliente->id_cliente);

        expect($aprobacion->estado)->toBe('Aprobada')
            ->and($this->orden->fresh()->cotizacion->estado)->toBe('Aprobada');
    });

    it('el cliente puede rechazar con motivo obligatorio', function () {
        $version = $this->service->crearCotizacion($this->orden, $this->detalles, $this->recepcion->id);

        $aprobacion = $this->service->rechazarPorCliente($version, $this->cliente->id_cliente, 'Muy caro');

        expect($aprobacion->estado)->toBe('Rechazada')
            ->and($aprobacion->motivo_rechazo)->toBe('Muy caro');
    });

    it('rechazar sin motivo lanza ValidationException', function () {
        $version = $this->service->crearCotizacion($this->orden, $this->detalles, $this->recepcion->id);

        expect(fn () => $this->service->rechazarPorCliente($version, $this->cliente->id_cliente, ''))
            ->toThrow(ValidationException::class);
    });

    it('cotización vencida no puede aceptarse', function () {
        $version = $this->service->crearCotizacion(
            $this->orden, $this->detalles, $this->recepcion->id,
            vigencia: now()->subDay()->toDateTime(),
        );

        expect(fn () => $this->service->aceptarPorCliente($version, $this->cliente->id_cliente))
            ->toThrow(ValidationException::class);
    });

    it('validar condiciones para reparación falla sin cotización', function () {
        expect(fn () => $this->service->validarCondicionesParaReparacion($this->orden))
            ->toThrow(ValidationException::class);
    });

    it('validar condiciones para reparación pasa con cotización aprobada', function () {
        $version = $this->service->crearCotizacion($this->orden, $this->detalles, $this->recepcion->id);
        $this->service->aceptarPorCliente($version, $this->cliente->id_cliente);

        expect(fn () => $this->service->validarCondicionesParaReparacion($this->orden->fresh()))->not->toThrow(ValidationException::class);
    });

    it('el administrador puede aprobar una cotización', function () {
        $version = $this->service->crearCotizacion($this->orden, $this->detalles, $this->recepcion->id);
        $this->service->aprobarPorAdministrador($version, $this->admin->id);

        expect($this->orden->fresh()->cotizacion->estado)->toBe('AprobadaAdmin');
    });
});
