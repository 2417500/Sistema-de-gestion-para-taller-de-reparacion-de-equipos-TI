<?php

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Equipo;
use App\Models\OrdenServicio;
use App\Models\Refaccion;
use App\Models\Role;
use App\Models\User;
use App\Services\CotizacionService;

beforeEach(function () {
    $this->service = new CotizacionService();

    $roleCliente = Role::create(['nombre' => 'Cliente', 'descripcion' => 'Cliente']);
    $roleRecepcion = Role::create(['nombre' => 'Recepcion', 'descripcion' => 'Recepción']);

    $this->clienteUser = User::create([
        'role_id' => $roleCliente->id,
        'name' => 'Cliente Sofía',
        'email' => 'sofia@tecnofix.test',
        'password' => bcrypt('password'),
    ]);

    $recepcion = User::create([
        'role_id' => $roleRecepcion->id,
        'name' => 'Recepcionista',
        'email' => 'recepcion@tecnofix.test',
        'password' => bcrypt('password'),
    ]);

    $cliente = Cliente::create([
        'user_id' => $this->clienteUser->id,
        'equipos_reparados_count' => 0,
        'aplica_descuento' => false,
    ]);

    $equipo = Equipo::create([
        'cliente_id' => $cliente->id,
        'tipo_equipo' => 'Laptop',
        'marca' => 'Dell',
        'modelo' => 'XPS 15',
        'numero_serie' => 'SN-DELL-XPS-99',
    ]);

    $this->orden = OrdenServicio::create([
        'codigo_servicio' => 'ST-2026-0010',
        'equipo_id' => $equipo->id,
        'recepcion_id' => $recepcion->id,
        'tipo_servicio' => 'Reparacion',
        'falla_reportada' => 'Pantalla rota y teclado fallando',
        'estado' => 'Pendiente',
    ]);

    $this->refaccion1 = Refaccion::create([
        'nombre' => 'Display OLED 15.6 4K',
        'marca' => 'Dell Original',
        'modelo' => 'OLED-XPS',
        'precio_unitario' => 2500.00,
        'stock_disponible' => 5,
    ]);

    $this->refaccion2 = Refaccion::create([
        'nombre' => 'Teclado Retroiluminado',
        'marca' => 'Dell',
        'modelo' => 'KB-XPS-US',
        'precio_unitario' => 600.00,
        'stock_disponible' => 10,
    ]);
});

test('crear cotizacion calcula subtotal con mano de obra y refacciones', function () {
    $manoDeObra = 800.00;
    $repuestos = [
        [
            'refaccion_id' => $this->refaccion1->id,
            'cantidad' => 1, // 2500.00
        ],
        [
            'refaccion_id' => $this->refaccion2->id,
            'cantidad' => 2, // 2 * 600 = 1200.00
        ],
    ];

    // Total esperado: 800 + 2500 + 1200 = 4500.00
    $cotizacion = $this->service->crearCotizacion($this->orden->id, $manoDeObra, $repuestos);

    expect($cotizacion->id)->not->toBeNull()
        ->and($cotizacion->version)->toBe(1)
        ->and((float) $cotizacion->subtotal)->toBe(4500.00)
        ->and($cotizacion->estado)->toBe('Pendiente')
        ->and($cotizacion->detalles)->toHaveCount(2);

    $this->assertDatabaseHas('detalle_cotizacions', [
        'cotizacion_id' => $cotizacion->id,
        'refaccion_id' => $this->refaccion2->id,
        'cantidad' => 2,
        'subtotal' => 1200.00,
    ]);
});

test('responder cotizacion aceptada actualiza orden a En_Proceso', function () {
    $cotizacion = $this->service->crearCotizacion($this->orden->id, 500.00, []);

    $cotizacionRespondida = $this->service->responderCotizacion(
        $cotizacion->id,
        'Aceptada',
        $this->clienteUser->id
    );

    expect($cotizacionRespondida->estado)->toBe('Aceptada');

    $this->orden->refresh();
    expect($this->orden->estado)->toBe('En_Proceso');

    $this->assertDatabaseHas('historial_estados', [
        'orden_servicio_id' => $this->orden->id,
        'modulo' => 'Cliente',
    ]);
});

test('responder cotizacion con decision invalida lanza excepcion', function () {
    $cotizacion = $this->service->crearCotizacion($this->orden->id, 300.00, []);

    expect(fn () => $this->service->responderCotizacion($cotizacion->id, 'Posiblemente', $this->clienteUser->id))
        ->toThrow(InvalidArgumentException::class);
});
