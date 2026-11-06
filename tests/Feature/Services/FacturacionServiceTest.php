<?php

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Equipo;
use App\Models\OrdenServicio;
use App\Models\Role;
use App\Models\User;
use App\Services\FacturacionService;

beforeEach(function () {
    $this->service = new FacturacionService();

    $roleCliente = Role::create(['nombre' => 'Cliente', 'descripcion' => 'Cliente']);
    $roleRecepcion = Role::create(['nombre' => 'Recepcion', 'descripcion' => 'Recepción']);

    $user1 = User::create([
        'role_id' => $roleCliente->id,
        'name' => 'Cliente Ocasional',
        'email' => 'ocasional@tecnofix.test',
        'password' => bcrypt('password'),
    ]);

    $this->clienteNormal = Cliente::create([
        'user_id' => $user1->id,
        'equipos_reparados_count' => 2, // 2 <= 5 -> No descuento
        'aplica_descuento' => false,
    ]);

    $user2 = User::create([
        'role_id' => $roleCliente->id,
        'name' => 'Cliente Frecuente',
        'email' => 'frecuente@tecnofix.test',
        'password' => bcrypt('password'),
    ]);

    $this->clienteVip = Cliente::create([
        'user_id' => $user2->id,
        'equipos_reparados_count' => 6, // 6 > 5 -> Aplica 15% Descuento
        'aplica_descuento' => true,
    ]);

    $this->recepcion = User::create([
        'role_id' => $roleRecepcion->id,
        'name' => 'Recepcionista',
        'email' => 'caja@tecnofix.test',
        'password' => bcrypt('password'),
    ]);
});

test('generar ticket sin descuento cuando cliente tiene 5 o menos equipos reparados', function () {
    $equipo = Equipo::create([
        'cliente_id' => $this->clienteNormal->id,
        'tipo_equipo' => 'PC',
        'marca' => 'Asus',
        'modelo' => 'ROG',
        'numero_serie' => 'SN-ROG-001',
    ]);

    $orden = OrdenServicio::create([
        'codigo_servicio' => 'ST-2026-0050',
        'equipo_id' => $equipo->id,
        'recepcion_id' => $this->recepcion->id,
        'tipo_servicio' => 'Reparacion',
        'falla_reportada' => 'Mantenimiento líquido',
        'estado' => 'Terminado',
    ]);

    Cotizacion::create([
        'orden_servicio_id' => $orden->id,
        'version' => 1,
        'costo_mano_obra' => 2000.00,
        'subtotal' => 2000.00,
        'estado' => 'Aceptada',
    ]);

    $ticket = $this->service->generarTicket($orden->id, 'Efectivo');

    expect((float) $ticket->subtotal)->toBe(2000.00)
        ->and((float) $ticket->porcentaje_descuento)->toBe(0.00)
        ->and((float) $ticket->monto_descuento)->toBe(0.00)
        ->and((float) $ticket->total_pagar)->toBe(2000.00);
});

test('generar ticket aplica 15 porciento descuento cuando cliente supera 5 equipos reparados', function () {
    $equipo = Equipo::create([
        'cliente_id' => $this->clienteVip->id,
        'tipo_equipo' => 'Laptop',
        'marca' => 'MacBook',
        'modelo' => 'Pro 14',
        'numero_serie' => 'SN-MAC-PRO-14',
    ]);

    $orden = OrdenServicio::create([
        'codigo_servicio' => 'ST-2026-0051',
        'equipo_id' => $equipo->id,
        'recepcion_id' => $this->recepcion->id,
        'tipo_servicio' => 'Reparacion',
        'falla_reportada' => 'Cambio de trackpad',
        'estado' => 'Terminado',
    ]);

    Cotizacion::create([
        'orden_servicio_id' => $orden->id,
        'version' => 1,
        'costo_mano_obra' => 2000.00,
        'subtotal' => 2000.00,
        'estado' => 'Aceptada',
    ]);

    $ticket = $this->service->generarTicket($orden->id, 'Tarjeta');

    // Subtotal: 2000.00 | Dcto 15%: 300.00 | Total a Pagar: 1700.00
    expect((float) $ticket->subtotal)->toBe(2000.00)
        ->and((float) $ticket->porcentaje_descuento)->toBe(15.00)
        ->and((float) $ticket->monto_descuento)->toBe(300.00)
        ->and((float) $ticket->total_pagar)->toBe(1700.00);
});

test('procesar cobro y entrega actualiza orden a Entregado e incrementa contador de equipos', function () {
    // Cliente con 5 equipos (en el umbral)
    $this->clienteNormal->update(['equipos_reparados_count' => 5]);

    $equipo = Equipo::create([
        'cliente_id' => $this->clienteNormal->id,
        'tipo_equipo' => 'Laptop',
        'marca' => 'Acer',
        'modelo' => 'Swift',
        'numero_serie' => 'SN-ACER-SWIFT',
    ]);

    $orden = OrdenServicio::create([
        'codigo_servicio' => 'ST-2026-0052',
        'equipo_id' => $equipo->id,
        'recepcion_id' => $this->recepcion->id,
        'tipo_servicio' => 'Reparacion',
        'falla_reportada' => 'Bisagra rota',
        'estado' => 'Terminado',
    ]);

    Cotizacion::create([
        'orden_servicio_id' => $orden->id,
        'version' => 1,
        'costo_mano_obra' => 1000.00,
        'subtotal' => 1000.00,
        'estado' => 'Aceptada',
    ]);

    $this->service->procesarCobroYEntrega($orden->id, 'Efectivo', $this->recepcion->id);

    $orden->refresh();
    expect($orden->estado)->toBe('Entregado');

    $this->clienteNormal->refresh();
    // Ahora tiene 6 equipos -> Pasa a ser elegible para descuento
    expect($this->clienteNormal->equipos_reparados_count)->toBe(6)
        ->and((bool) $this->clienteNormal->aplica_descuento)->toBeTrue();

    $this->assertDatabaseHas('historial_estados', [
        'orden_servicio_id' => $orden->id,
        'modulo' => 'Facturación',
        'estado' => 'Entregado',
    ]);
});
