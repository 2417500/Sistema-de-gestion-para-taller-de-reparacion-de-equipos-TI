<?php

use App\Models\AprobacionAdmin;
use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\OrdenServicio;
use App\Models\Role;
use App\Models\User;
use App\Services\AprobacionAdminService;

beforeEach(function () {
    $this->service = new AprobacionAdminService();

    $roleAdmin = Role::create(['nombre' => 'Administrador', 'descripcion' => 'Administrador']);
    $roleRecepcion = Role::create(['nombre' => 'Recepcion', 'descripcion' => 'Recepción']);

    $this->admin = User::create([
        'role_id' => $roleAdmin->id,
        'name' => 'Admin Jorge',
        'email' => 'admin@tecnofix.test',
        'password' => bcrypt('password'),
    ]);

    $recepcion = User::create([
        'role_id' => $roleRecepcion->id,
        'name' => 'Recepcionista',
        'email' => 'recepcion_aprob@tecnofix.test',
        'password' => bcrypt('password'),
    ]);

    $cliente = Cliente::create([
        'user_id' => $this->admin->id,
        'equipos_reparados_count' => 0,
        'aplica_descuento' => false,
    ]);

    $equipo = Equipo::create([
        'cliente_id' => $cliente->id,
        'tipo_equipo' => 'Servidor',
        'marca' => 'HPE',
        'modelo' => 'ProLiant DL380',
        'numero_serie' => 'SN-HPE-SERVER',
    ]);

    $this->orden = OrdenServicio::create([
        'codigo_servicio' => 'ST-2026-0099',
        'equipo_id' => $equipo->id,
        'recepcion_id' => $recepcion->id,
        'tipo_servicio' => 'Reparacion',
        'falla_reportada' => 'Fallo en controladora RAID y ventiladores redundantes',
        'estado' => 'En_Proceso',
    ]);
});

test('solicitar aprobacion administrativa activa bandera en la orden de servicio', function () {
    $solicitud = $this->service->solicitarAprobacion(
        $this->orden->id,
        'Exceso_Refacciones',
        'Se requieren 4 discos SAS empresariales para reemplazo simultáneo.'
    );

    expect($solicitud->id)->not->toBeNull()
        ->and($solicitud->estado)->toBe('Pendiente')
        ->and($solicitud->tipo_solicitud)->toBe('Exceso_Refacciones');

    $this->orden->refresh();
    expect((bool) $this->orden->requiere_aprobacion_admin)->toBeTrue();

    $this->assertDatabaseHas('historial_estados', [
        'orden_servicio_id' => $this->orden->id,
        'modulo' => 'Administrador',
    ]);
});

test('resolver solicitud como Aprobado por el admin apaga bandera', function () {
    $solicitud = $this->service->solicitarAprobacion(
        $this->orden->id,
        'Exceso_Refacciones',
        'Solicitud inicial'
    );

    $solicitudResuelta = $this->service->resolverSolicitud(
        $solicitud->id,
        $this->admin->id,
        'Aprobado',
        'Autorizado por presupuesto de cliente corporativo'
    );

    expect($solicitudResuelta->estado)->toBe('Aprobado')
        ->and($solicitudResuelta->admin_id)->toBe($this->admin->id);

    $this->orden->refresh();
    expect((bool) $this->orden->requiere_aprobacion_admin)->toBeFalse();
});

test('resolver solicitud como Rechazado por el admin apaga bandera', function () {
    $solicitud = $this->service->solicitarAprobacion(
        $this->orden->id,
        'Exceso_Equipos',
        'Ingreso masivo sin cita'
    );

    $solicitudResuelta = $this->service->resolverSolicitud(
        $solicitud->id,
        $this->admin->id,
        'Rechazado',
        'Cupo de banco de trabajo saturado'
    );

    expect($solicitudResuelta->estado)->toBe('Rechazado');

    $this->orden->refresh();
    expect((bool) $this->orden->requiere_aprobacion_admin)->toBeFalse();
});
