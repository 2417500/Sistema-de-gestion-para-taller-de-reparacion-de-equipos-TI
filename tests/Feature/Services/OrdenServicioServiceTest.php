<?php

use App\Models\Garantia;
use App\Models\OrdenServicio;
use App\Services\OrdenServicioService;
use Illuminate\Validation\ValidationException;

// ──────────────────────────────────────────────────────────────────────────────
// OrdenServicioService – Órdenes, folios, estados y cancelaciones
// ──────────────────────────────────────────────────────────────────────────────

describe('OrdenServicioService', function () {

    beforeEach(function () {
        $roles = crearRoles();
        $datos = crearClienteConUsuario($roles['cliente']);

        $this->cliente      = $datos['cliente'];
        $this->userCliente  = $datos['user'];
        $this->recepcion    = crearUsuarioEmpleado($roles['recepcion'], 'rec@taller.com');
        $this->equipo       = crearEquipo($this->cliente->id_cliente);
        $this->service      = app(OrdenServicioService::class);
    });

    it('crea una orden con folio único', function () {
        $orden = $this->service->crearOrden(
            datosOrden: ['falla_reportada' => 'No enciende', 'accesorios_recibidos' => 'Cargador'],
            idRecepcionista: $this->recepcion->id,
            idEquipo: $this->equipo->id_equipo,
        );

        expect($orden)->toBeInstanceOf(OrdenServicio::class)
            ->and($orden->folio)->not->toBeNull()
            ->and($orden->estado_orden)->toBe('Recibida')
            ->and($orden->es_reingreso)->toBeFalse();
    });

    it('el folio generado es único', function () {
        // Dos clientes distintos, cada uno con su propio equipo y orden
        $rolCliente = \App\Models\Rol::where('nombre_rol', 'Cliente')->first();
        $datos2 = crearClienteConUsuario($rolCliente, ['correo_contacto' => 'otro@mail.com']);
        $equipo2 = crearEquipo($datos2['cliente']->id_cliente);

        $o1 = $this->service->crearOrden(
            ['falla_reportada' => 'Falla 1'], $this->recepcion->id, $this->equipo->id_equipo
        );
        $o2 = $this->service->crearOrden(
            ['falla_reportada' => 'Falla 2'], $this->recepcion->id, $equipo2->id_equipo
        );

        expect($o1->folio)->not->toBe($o2->folio);
    });

    it('registra historial al crear la orden', function () {
        $orden = $this->service->crearOrden(
            ['falla_reportada' => 'Pantalla rota'], $this->recepcion->id, $this->equipo->id_equipo
        );

        expect($orden->historialEstados()->where('estado_nuevo', 'Recibida')->exists())->toBeTrue();
    });

    it('cambia el estado de una orden correctamente', function () {
        $orden = crearOrden($this->equipo->id_equipo, $this->recepcion->id);

        $this->service->cambiarEstado($orden, 'En Diagnóstico', $this->recepcion->id, 'Iniciando diagnóstico');

        expect($orden->fresh()->estado_orden)->toBe('En Diagnóstico');
    });

    it('no puede cambiar estado de una orden Entregada', function () {
        $orden = crearOrden($this->equipo->id_equipo, $this->recepcion->id, ['estado_orden' => 'Entregada']);

        expect(fn () => $this->service->cambiarEstado($orden, 'En Diagnóstico', $this->recepcion->id))
            ->toThrow(ValidationException::class);
    });

    it('cancela una orden en estado Recibida', function () {
        $orden = crearOrden($this->equipo->id_equipo, $this->recepcion->id);

        $this->service->cancelarOrden($orden, $this->recepcion->id, 'Cliente desistió');

        expect($orden->fresh()->estado_orden)->toBe('Cancelada');
    });

    it('no cancela ordinariamente una orden En Reparación', function () {
        $orden = crearOrden($this->equipo->id_equipo, $this->recepcion->id, ['estado_orden' => 'En Reparación']);

        expect(fn () => $this->service->cancelarOrden($orden, $this->recepcion->id, 'Motivo'))
            ->toThrow(ValidationException::class);
    });

    it('rechaza segundo equipo sin autorización del administrador', function () {
        // Primera orden activa del cliente
        crearOrden($this->equipo->id_equipo, $this->recepcion->id);

        // Segundo equipo sin autorización
        $equipo2 = crearEquipo($this->cliente->id_cliente);

        expect(fn () => $this->service->crearOrden(
            ['falla_reportada' => 'Falla 2'], $this->recepcion->id, $equipo2->id_equipo
        ))->toThrow(ValidationException::class);
    });

    it('crea reingreso por garantía vigente', function () {
        // Crear orden entregada con garantía
        $ordenOriginal = crearOrden($this->equipo->id_equipo, $this->recepcion->id, ['estado_orden' => 'Entregada']);
        Garantia::create([
            'id_orden'    => $ordenOriginal->id_orden,
            'id_cliente'  => $this->cliente->id_cliente,
            'id_usuario'  => $this->recepcion->id,
            'fecha_inicio' => today(),
            'fecha_fin'   => today()->addMonths(3),
            'condiciones' => 'Garantía estándar',
            'estado'      => 'Vigente',
        ]);

        $reingreso = $this->service->crearReingreso(
            $ordenOriginal,
            ['falla_reportada' => 'Volvió a fallar'],
            $this->recepcion->id,
        );

        expect($reingreso->es_reingreso)->toBeTrue()
            ->and($reingreso->id_orden_origen)->toBe($ordenOriginal->id_orden)
            ->and($reingreso->folio)->toStartWith('RE-');
    });
});
