<?php

use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\OrdenServicio;
use App\Models\Role;
use App\Models\User;
use App\Services\DiagnosticoService;

beforeEach(function () {
    $this->service = new DiagnosticoService();

    $roleTecnico = Role::create(['nombre' => 'Tecnico', 'descripcion' => 'Técnico']);
    $this->tecnico = User::create([
        'role_id' => $roleTecnico->id,
        'name' => 'Técnico Daniel',
        'email' => 'daniel@tecnofix.test',
        'password' => bcrypt('password'),
    ]);

    $roleRecepcion = Role::create(['nombre' => 'Recepcion', 'descripcion' => 'Recepción']);
    $recepcion = User::create([
        'role_id' => $roleRecepcion->id,
        'name' => 'Recepcionista Maria',
        'email' => 'maria@tecnofix.test',
        'password' => bcrypt('password'),
    ]);

    $cliente = Cliente::create([
        'user_id' => $this->tecnico->id,
        'equipos_reparados_count' => 0,
        'aplica_descuento' => false,
    ]);

    $equipo = Equipo::create([
        'cliente_id' => $cliente->id,
        'tipo_equipo' => 'PC',
        'marca' => 'Corsair',
        'modelo' => 'Custom Rig',
        'numero_serie' => 'SN-COR-001',
    ]);

    $this->orden = OrdenServicio::create([
        'codigo_servicio' => 'ST-2026-0005',
        'equipo_id' => $equipo->id,
        'recepcion_id' => $recepcion->id,
        'tipo_servicio' => 'Diagnostico',
        'falla_reportada' => 'No da señal de video',
        'estado' => 'Pendiente',
    ]);
});

test('registrar diagnostico tecnico gratuito y avanzar estado a En_Proceso', function () {
    $diagnostico = $this->service->registrarDiagnostico(
        $this->orden->id,
        $this->tecnico->id,
        'Cortocircuito en VRM de GPU detectado en línea de 12V.',
        'Sustituir MOSFETs de potencia y reballing de control térmico.',
        0.00 // Política de diagnóstico gratuito
    );

    expect($diagnostico->id)->not->toBeNull()
        ->and((float) $diagnostico->costo_diagnostico)->toBe(0.00);

    $this->assertDatabaseHas('diagnosticos', [
        'orden_servicio_id' => $this->orden->id,
        'tecnico_id' => $this->tecnico->id,
    ]);

    $this->orden->refresh();
    expect($this->orden->estado)->toBe('En_Proceso');

    $this->assertDatabaseHas('historial_estados', [
        'orden_servicio_id' => $this->orden->id,
        'modulo' => 'Técnico',
        'estado' => 'En_Proceso',
    ]);
});

test('obtener diagnosticos por orden de servicio', function () {
    $this->service->registrarDiagnostico(
        $this->orden->id,
        $this->tecnico->id,
        'Diagnóstico preliminar: memoria RAM sulfatada.',
        'Limpieza ultrasónica de contactos.'
    );

    $diagnosticos = $this->service->obtenerDiagnosticosPorOrden($this->orden->id);

    expect($diagnosticos)->toHaveCount(1)
        ->and($diagnosticos->first()->tecnico_id)->toBe($this->tecnico->id);
});
