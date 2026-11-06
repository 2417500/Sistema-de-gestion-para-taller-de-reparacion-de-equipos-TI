<?php

use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\Herramienta;
use App\Models\OrdenServicio;
use App\Models\Role;
use App\Models\User;
use App\Services\HerramientaService;

beforeEach(function () {
    $this->service = new HerramientaService();

    $roleTecnico = Role::create(['nombre' => 'Tecnico', 'descripcion' => 'Técnico']);
    $roleRecepcion = Role::create(['nombre' => 'Recepcion', 'descripcion' => 'Recepción']);

    $this->tecnico = User::create([
        'role_id' => $roleTecnico->id,
        'name' => 'Técnico Andrés',
        'email' => 'andres@tecnofix.test',
        'password' => bcrypt('password'),
    ]);

    $recepcion = User::create([
        'role_id' => $roleRecepcion->id,
        'name' => 'Recepcion',
        'email' => 'recepcion2@tecnofix.test',
        'password' => bcrypt('password'),
    ]);

    $cliente = Cliente::create([
        'user_id' => $this->tecnico->id,
        'equipos_reparados_count' => 0,
        'aplica_descuento' => false,
    ]);

    $equipo = Equipo::create([
        'cliente_id' => $cliente->id,
        'tipo_equipo' => 'Laptop',
        'marca' => 'Apple',
        'modelo' => 'MacBook Air',
        'numero_serie' => 'SN-MAC-88',
    ]);

    $this->orden = OrdenServicio::create([
        'codigo_servicio' => 'ST-2026-0030',
        'equipo_id' => $equipo->id,
        'recepcion_id' => $recepcion->id,
        'tipo_servicio' => 'Reparacion',
        'falla_reportada' => 'Reemplazo de chip SMC',
        'estado' => 'En_Proceso',
    ]);

    $this->estacionSoldar = Herramienta::create([
        'nombre' => 'Estación de Soldar JBC Aire Caliente',
        'marca' => 'JBC Tools',
        'modelo' => 'JT-D',
        'numero_serie' => 'SN-JBC-55',
        'stock_total' => 5,
        'stock_disponible' => 5,
    ]);
});

test('solicitar prestamo normal descuenta stock inmediatamente y aprueba', function () {
    // Solicita 1 unidad (no excede el umbral de 2)
    $prestamo = $this->service->solicitarPrestamo(
        $this->orden->id,
        $this->estacionSoldar->id,
        $this->tecnico->id,
        1
    );

    expect($prestamo->estado_prestamo)->toBe('Aprobado_Admin');

    $this->estacionSoldar->refresh();
    expect($this->estacionSoldar->stock_disponible)->toBe(4);
});

test('solicitar exceso de herramientas crea solicitud de aprobacion admin', function () {
    // Solicita 3 unidades -> Requiere autorización administrativa
    $prestamo = $this->service->solicitarPrestamo(
        $this->orden->id,
        $this->estacionSoldar->id,
        $this->tecnico->id,
        3
    );

    expect($prestamo->estado_prestamo)->toBe('Solicitado');

    $this->assertDatabaseHas('aprobacion_admins', [
        'orden_servicio_id' => $this->orden->id,
        'tipo_solicitud' => 'Exceso_Herramientas',
        'estado' => 'Pendiente',
    ]);

    $this->orden->refresh();
    expect((bool) $this->orden->requiere_aprobacion_admin)->toBeTrue();
});

test('devolver herramienta restituye stock disponible al inventario', function () {
    $prestamo = $this->service->solicitarPrestamo(
        $this->orden->id,
        $this->estacionSoldar->id,
        $this->tecnico->id,
        1
    );

    $prestamoDevuelto = $this->service->devolverHerramienta($prestamo->id);

    expect($prestamoDevuelto->estado_prestamo)->toBe('Devuelto')
        ->and($prestamoDevuelto->fecha_devolucion)->not->toBeNull();

    $this->estacionSoldar->refresh();
    expect($this->estacionSoldar->stock_disponible)->toBe(5);
});
