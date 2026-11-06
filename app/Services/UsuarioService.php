<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * UsuarioService
 * --------------
 * Gestiona el registro de usuarios y clientes desde el portal público
 * y desde recepción.
 *
 * Reglas de negocio cubiertas:
 *  - Todo usuario registrado desde el portal público recibe el rol "Cliente".
 *  - No puede seleccionar ni asignarse otro rol.
 *  - Los clientes pueden registrarse solos o recepción puede hacerlo por ellos.
 */
class UsuarioService
{
    public function __construct(
        private readonly BitacoraService $bitacora,
    ) {}

    /**
     * Registro desde el portal público (auto-registro).
     * Siempre asigna el rol "Cliente".
     *
     * @param  array{name:string, email:string, password:string, telefono:string, nombre:string, apellido:string} $datos
     */
    public function registrarDesdePortal(array $datos): User
    {
        return DB::transaction(function () use ($datos) {
            $rolCliente = $this->obtenerRolCliente();

            $cliente = Cliente::create([
                'nombre'           => $datos['nombre'],
                'apellido'         => $datos['apellido'],
                'telefono'         => $datos['telefono'],
                'correo_contacto'  => $datos['email'],
                'fecha_registro'   => today(),
            ]);

            $user = User::create([
                'name'       => $datos['name'],
                'email'      => $datos['email'],
                'password'   => Hash::make($datos['password']),
                'id_rol'     => $rolCliente->id_rol,
                'id_cliente' => $cliente->id_cliente,
            ]);

            $this->bitacora->registrar('registro_usuario', 'User', $user->id,
                'Auto-registro desde portal público', $user->id);

            return $user;
        });
    }

    /**
     * Registro realizado por recepción en nombre de un cliente.
     * También asigna el rol "Cliente"; recepción no puede asignar otro rol.
     */
    public function registrarPorRecepcion(array $datos, int $idRecepcionista): User
    {
        return DB::transaction(function () use ($datos, $idRecepcionista) {
            $rolCliente = $this->obtenerRolCliente();

            $cliente = Cliente::create([
                'nombre'          => $datos['nombre'],
                'apellido'        => $datos['apellido'],
                'telefono'        => $datos['telefono'],
                'correo_contacto' => $datos['email'] ?? null,
                'direccion'       => $datos['direccion'] ?? null,
                'fecha_registro'  => today(),
            ]);

            $user = User::create([
                'name'       => trim("{$datos['nombre']} {$datos['apellido']}"),
                'email'      => $datos['email'],
                'password'   => Hash::make($datos['password']),
                'id_rol'     => $rolCliente->id_rol,
                'id_cliente' => $cliente->id_cliente,
            ]);

            $this->bitacora->registrar('registro_usuario', 'User', $user->id,
                'Registro por recepción', $idRecepcionista);

            return $user;
        });
    }

    /**
     * Devuelve el rol "Cliente". Lanza excepción si no existe en BD.
     */
    private function obtenerRolCliente(): Rol
    {
        $rol = Rol::where('nombre_rol', 'Cliente')->first();

        if (! $rol) {
            throw ValidationException::withMessages([
                'rol' => 'El rol "Cliente" no está configurado en el sistema.',
            ]);
        }

        return $rol;
    }
}
