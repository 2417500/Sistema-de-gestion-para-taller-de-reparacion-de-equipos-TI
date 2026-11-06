<?php

use App\Models\Cliente;
use App\Models\Equipo;
use App\Models\OrdenServicio;
use App\Models\Role;
use App\Models\User;
use App\Services\OrdenServicioService;

beforeEach(function () {
    $this->service = new OrdenServicioService();

    $roleRecepcion = Role::create(['nombre' => 'Recepcion', 'descripcion' => 'Recepción']);
    $roleTecnico = Role::create(['nombre' => 'Tecnico', 'descripcion' => 'Técnico']);
    $roleCliente = Role::create(['nombre' => 'Cliente', 'descripcion' => 'Cliente']);

    $this->recepcionUser = User::create([
        'role_id' => $roleRecepcion->id,
        'name' => 'Recepcionista Ana',
        'email' => 'ana@tecnofix.test',
        'password' => bcrypt('secret123'),
    ]);

    $this->tecnicoUser = User::create([
        'role_id' => $roleTecnico->id,
        'name' => 'Técnico Carlos',
        'email' => 'carlos@tecnofix.test',
        'password' => bcrypt('secret123'),
    ]);

    $userCliente = User::create([
        'role_id' => $roleCliente->id,
        'name' => 'Cliente Roberto',
        'email' => 'roberto@cliente.test',
        'password' => bcrypt('secret123'),
    ]);

    $this->cliente = Cliente::create([
        'user_id' => $userCliente->id,
        'equipos_reparados_count' => 0,
        'aplica_descuento' => false,
    ]);
});

test('crear orden registra equipo y genera codigo de servicio con formato ST-YYYY-XXXX', function () {
    $equipoData = [
        'cliente_id' => $this->cliente->id,
        'tipo_equipo' => 'Laptop',
        'marca' => 'Lenovo',
        'modelo' => 'ThinkPad T14',
        'numero_serie' => 'SN-LNV-998877',
    ];

    $ordenData = [
        'recepcion_id' => $this->recepcionUser->id,
        'tipo_servicio' => 'Reparacion',
        'falla_reportada' => 'No enciende tras descarga eléctrica',
    ];

    $evidencias = [
        [
            'ruta_imagen' => 'evidencias/chasis_lateral.jpg',
            'descripcion_estado' => 'Rayón superficial en tapa inferior',
        ]
    ];

    $orden = $this->service->crearOrden($equipoData, $ordenData, $evidencias);

    expect($orden->id)->not->toBeNull()
        ->and($orden->codigo_servicio)->toStartWith('ST-' . date('Y') . '-')
        ->and($orden->estado)->toBe('Pendiente')
        ->and($orden->evidencias)->toHaveCount(1);

    $this->assertDatabaseHas('equipos', ['numero_serie' => 'SN-LNV-998877']);
    $this->assertDatabaseHas('historial_estados', [
        'orden_servicio_id' => $orden->id,
        'modulo' => 'Recepción',
        'estado' => 'Pendiente',
    ]);
});

test('asignar tecnico a orden actualiza tecnico_id y cambia estado a En_Proceso', function () {
    $equipo = Equipo::create([
        'cliente_id' => $this->cliente->id,
        'tipo_equipo' => 'PC',
        'marca' => 'Dell',
        'modelo' => 'OptiPlex 7090',
        'numero_serie' => 'SN-DELL-112233',
    ]);

    $orden = OrdenServicio::create([
        'codigo_servicio' => 'ST-2026-0001',
        'equipo_id' => $equipo->id,
        'recepcion_id' => $this->recepcionUser->id,
        'tipo_servicio' => 'Diagnostico',
        'falla_reportada' => 'Pantallazo azul recurrente',
        'estado' => 'Pendiente',
    ]);

    $ordenActualizada = $this->service->asignarTecnico($orden->id, $this->tecnicoUser->id);

    expect($ordenActualizada->tecnico_id)->toBe($this->tecnicoUser->id)
        ->and($ordenActualizada->estado)->toBe('En_Proceso');

    $this->assertDatabaseHas('historial_estados', [
        'orden_servicio_id' => $orden->id,
        'modulo' => 'Técnico',
    ]);
});

test('actualizar estado de orden valida estados permitidos y asienta en bitacora', function () {
    $equipo = Equipo::create([
        'cliente_id' => $this->cliente->id,
        'tipo_equipo' => 'Laptop',
        'marca' => 'HP',
        'modelo' => 'ProBook',
        'numero_serie' => 'SN-HP-445566',
    ]);

    $orden = OrdenServicio::create([
        'codigo_servicio' => 'ST-2026-0002',
        'equipo_id' => $equipo->id,
        'recepcion_id' => $this->recepcionUser->id,
        'tecnico_id' => $this->tecnicoUser->id,
        'tipo_servicio' => 'Reparacion',
        'falla_reportada' => 'Teclado no responde',
        'estado' => 'En_Proceso',
    ]);

    $ordenFinal = $this->service->actualizarEstado(
        $orden->id,
        'Terminado',
        $this->tecnicoUser->id,
        'Pruebas QA superadas'
    );

    expect($ordenFinal->estado)->toBe('Terminado');

    expect(fn () => $this->service->actualizarEstado($orden->id, 'Estado_Inexistente', $this->tecnicoUser->id))
        ->toThrow(InvalidArgumentException::class);
});

test('buscar orden por codigo de servicio y por numero de serie', function () {
    $equipo = Equipo::create([
        'cliente_id' => $this->cliente->id,
        'tipo_equipo' => 'Laptop',
        'marca' => 'Asus',
        'modelo' => 'ZenBook',
        'numero_serie' => 'SN-ASUS-778899',
    ]);

    $orden = OrdenServicio::create([
        'codigo_servicio' => 'ST-2026-9999',
        'equipo_id' => $equipo->id,
        'recepcion_id' => $this->recepcionUser->id,
        'tipo_servicio' => 'Reparacion',
        'falla_reportada' => 'Batería inflada',
        'estado' => 'Pendiente',
    ]);

    $encontrada = $this->service->buscarPorCodigo('ST-2026-9999');
    expect($encontrada)->not->toBeNull()
        ->and($encontrada->id)->toBe($orden->id);

    $porSerie = $this->service->buscarPorNumeroSerie('SN-ASUS-778899');
    expect($porSerie)->toHaveCount(1)
        ->and($porSerie->first()->codigo_servicio)->toBe('ST-2026-9999');
});
