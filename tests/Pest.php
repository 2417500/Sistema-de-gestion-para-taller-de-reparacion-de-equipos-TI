<?php

/*
|--------------------------------------------------------------------------
| Pest Bootstrap – Tests para Sistema TSU GETI
|--------------------------------------------------------------------------
| Configuración global de Pest: importamos el TestCase de Laravel,
| habilitamos RefreshDatabase en todos los tests y definimos helpers
| reutilizables para los tests de Services.
*/

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Empleado;
use App\Models\Equipo;
use App\Models\OrdenServicio;
use App\Models\Pago;
use App\Models\Refaccion;
use App\Models\ReglaDescuento;
use App\Models\Rol;
use App\Models\TipoEquipo;
use App\Models\User;
use App\Models\VersionCotizacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(Tests\TestCase::class, RefreshDatabase::class)->in('Feature');

// ──────────────────────────────────────────────────────────────────────────────
// Helpers globales
// ──────────────────────────────────────────────────────────────────────────────

/**
 * Crea los roles básicos del sistema en BD.
 */
function crearRoles(): array
{
    return [
        'cliente'  => Rol::create(['nombre_rol' => 'Cliente',        'descripcion' => 'Cliente del taller']),
        'admin'    => Rol::create(['nombre_rol' => 'Administrador',   'descripcion' => 'Administrador']),
        'tecnico'  => Rol::create(['nombre_rol' => 'Técnico',         'descripcion' => 'Técnico reparador']),
        'recepcion'=> Rol::create(['nombre_rol' => 'Recepcionista',   'descripcion' => 'Recepcionista']),
    ];
}

/**
 * Crea un cliente con su usuario asociado.
 */
function crearClienteConUsuario(Rol $rolCliente, array $extra = []): array
{
    $cliente = Cliente::create(array_merge([
        'nombre'          => 'Juan',
        'apellido'        => 'Pérez',
        'telefono'        => '5551234567',
        'correo_contacto' => 'juan@ejemplo.com',
        'fecha_registro'  => today(),
    ], $extra));

    $user = User::create([
        'name'       => 'Juan Pérez',
        'email'      => $extra['correo_contacto'] ?? 'juan@ejemplo.com',
        'password'   => Hash::make('password'),
        'id_rol'     => $rolCliente->id_rol,
        'id_cliente' => $cliente->id_cliente,
    ]);

    return compact('cliente', 'user');
}

/**
 * Crea un usuario empleado (técnico, recepcionista, admin).
 * Orden correcto: User → Empleado(user_id, numero_empleado) → User.id_empleado
 */
function crearUsuarioEmpleado(Rol $rol, string $email = 'empleado@taller.com'): User
{
    // 1. Crear el User sin id_empleado todavía
    $user = User::create([
        'name'     => 'Ana García',
        'email'    => $email,
        'password' => Hash::make('password'),
        'id_rol'   => $rol->id_rol,
    ]);

    // 2. Crear el Empleado referenciando al user ya existente
    $empleado = Empleado::create([
        'user_id'          => $user->id,
        'numero_empleado'  => 'EMP-' . strtoupper(substr(uniqid(), -6)),
        'puesto'           => $rol->nombre_rol,
        'fecha_contratacion' => today(),
        'estado'           => true,
    ]);

    // 3. Vincular el id_empleado al usuario
    $user->update(['id_empleado' => $empleado->id_empleado]);

    return $user->fresh();
}

/**
 * Crea un equipo para el cliente dado.
 */
function crearEquipo(int $idCliente, array $extra = []): Equipo
{
    $tipo = TipoEquipo::firstOrCreate(
        ['nombre_tipo' => 'Laptop'],
        ['descripcion' => 'Laptop genérica', 'estado' => true]
    );

    return Equipo::create(array_merge([
        'id_cliente'     => $idCliente,
        'id_tipo_equipo' => $tipo->id_tipo_equipo,
        'marca'          => 'Dell',
        'modelo'         => 'Inspiron 15',
        'numero_serie'   => 'SN-' . uniqid(),
        'estado'         => true,
    ], $extra));
}

/**
 * Crea una orden de servicio básica.
 */
function crearOrden(int $idEquipo, int $idRecepcionista, array $extra = []): OrdenServicio
{
    return OrdenServicio::create(array_merge([
        'folio'                 => 'OS-TEST-' . strtoupper(substr(uniqid(), -5)),
        'id_equipo'             => $idEquipo,
        'id_recepcionista'      => $idRecepcionista,
        'fecha_recepcion'       => now(),
        'falla_reportada'       => 'No enciende',
        'estado_orden'          => 'Recibida',
        'es_reingreso'          => false,
    ], $extra));
}

/**
 * Crea una refacción en inventario.
 */
function crearRefaccion(int $existencia = 10, float $precio = 150.0, array $extra = []): Refaccion
{
    return Refaccion::create(array_merge([
        'nombre'            => 'RAM 8GB DDR4',
        'descripcion'       => 'Módulo de memoria',
        'existencia'        => $existencia,
        'existencia_minima' => 2,
        'precio_venta'      => $precio,
        'costo_unitario'    => 80.0,
        'estado'            => true,
    ], $extra));
}

/**
 * Crea una cotización en estado Aprobada con versión vigente.
 */
function crearCotizacionAprobada(OrdenServicio $orden, float $total = 1000.0): Cotizacion
{
    $cotizacion = Cotizacion::create([
        'id_orden'           => $orden->id_orden,
        'estado'             => 'Aprobada',
        'requiere_anticipo'  => true,
        'porcentaje_anticipo' => 50,
    ]);

    VersionCotizacion::create([
        'id_cotizacion'    => $cotizacion->id_cotizacion,
        'id_usuario'       => $orden->id_recepcionista,
        'numero_version'   => 1,
        'subtotal'         => $total,
        'descuento'        => 0,
        'porcentaje_descuento_aplicado' => 0,
        'total'            => $total,
        'fecha_emision'    => now()->subDay(),
        'fecha_vencimiento' => now()->addDays(7),
        'fecha_aceptacion' => now(),
    ]);

    return $cotizacion->fresh(['versionActual']);
}

/**
 * Crea un pago de anticipo para la orden.
 */
function crearAnticipo(OrdenServicio $orden, int $idCliente, int $idUsuario, float $monto): Pago
{
    return Pago::create([
        'id_orden'    => $orden->id_orden,
        'id_cliente'  => $idCliente,
        'id_usuario'  => $idUsuario,
        'monto'       => $monto,
        'metodo_pago' => 'Efectivo',
        'fecha_pago'  => now(),
        'estado'      => 'Registrado',
    ]);
}
