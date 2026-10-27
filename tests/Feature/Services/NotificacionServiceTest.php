<?php

use App\Models\Notificacion;
use App\Services\NotificacionService;

// ──────────────────────────────────────────────────────────────────────────────
// NotificacionService – Notificaciones internas del sistema
// ──────────────────────────────────────────────────────────────────────────────

describe('NotificacionService', function () {

    beforeEach(function () {
        $roles = crearRoles();
        $this->roles = $roles;

        $datos = crearClienteConUsuario($roles['cliente']);
        $this->cliente  = $datos['cliente'];
        $this->userCliente = $datos['user'];

        $this->service = app(NotificacionService::class);
    });

    it('crea una notificación para el usuario indicado', function () {
        $notif = $this->service->notificar(
            idUsuario: $this->userCliente->id,
            titulo: 'Prueba',
            mensaje: 'Mensaje de prueba',
            tipo: 'General',
        );

        expect($notif)->toBeInstanceOf(Notificacion::class)
            ->and($notif->titulo)->toBe('Prueba')
            ->and($notif->leida)->toBeFalse()
            ->and($notif->id_usuario)->toBe($this->userCliente->id);
    });

    it('notifica a todos los usuarios de un rol', function () {
        // Crear dos recepcionistas con el mismo rol
        $r1 = crearUsuarioEmpleado($this->roles['recepcion'], 'r1@taller.com');
        $r2 = crearUsuarioEmpleado($this->roles['recepcion'], 'r2@taller.com');

        $this->service->notificarRol('Recepcionista', 'Alerta', 'Hay un equipo pendiente');

        expect(Notificacion::where('titulo', 'Alerta')->count())->toBe(2);
    });

    it('genera notificación de cotización lista al cliente', function () {
        $this->service->cotizacionGenerada($this->cliente->id_cliente, 'OS-TEST-001');

        $notif = Notificacion::where('tipo', 'Cotizacion')->first();
        expect($notif)->not->toBeNull()
            ->and($notif->titulo)->toContain('Cotización')
            ->and($notif->mensaje)->toContain('OS-TEST-001');
    });

    it('genera notificación de cambio de estado al cliente', function () {
        $this->service->cambioEstadoOrden($this->cliente->id_cliente, 'OS-001', 'En Diagnóstico');

        $notif = Notificacion::where('tipo', 'Orden')->first();
        expect($notif)->not->toBeNull()
            ->and($notif->mensaje)->toContain('En Diagnóstico');
    });

    it('genera notificación de pago registrado al cliente', function () {
        $this->service->pagoRegistrado($this->cliente->id_cliente, 'OS-001', '$500.00');

        $notif = Notificacion::where('tipo', 'Pago')->first();
        expect($notif)->not->toBeNull()
            ->and($notif->mensaje)->toContain('$500.00');
    });

    it('genera notificación de equipo listo para entrega', function () {
        $this->service->equipoListoParaEntrega($this->cliente->id_cliente, 'OS-001');

        $notif = Notificacion::where('tipo', 'Entrega')->first();
        expect($notif)->not->toBeNull()
            ->and($notif->titulo)->toContain('listo');
    });

    it('genera alerta de stock bajo a administradores', function () {
        crearUsuarioEmpleado($this->roles['admin'], 'admin@taller.com');

        $this->service->stockBajo('RAM 8GB', 1);

        $notif = Notificacion::where('tipo', 'Inventario')->first();
        expect($notif)->not->toBeNull()
            ->and($notif->mensaje)->toContain('RAM 8GB');
    });
});
