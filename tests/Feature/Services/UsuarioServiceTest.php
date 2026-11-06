<?php

use App\Models\Cliente;
use App\Models\User;
use App\Services\UsuarioService;
use Illuminate\Validation\ValidationException;

// ──────────────────────────────────────────────────────────────────────────────
// UsuarioService – Registro de usuarios
// ──────────────────────────────────────────────────────────────────────────────

describe('UsuarioService', function () {

    beforeEach(function () {
        $this->roles   = crearRoles();
        $this->service = app(UsuarioService::class);
    });

    it('registra desde el portal y asigna el rol Cliente', function () {
        $user = $this->service->registrarDesdePortal([
            'name'     => 'María López',
            'email'    => 'maria@mail.com',
            'password' => 'secreto123',
            'nombre'   => 'María',
            'apellido' => 'López',
            'telefono' => '5551112222',
        ]);

        expect($user)->toBeInstanceOf(User::class)
            ->and($user->id_rol)->toBe($this->roles['cliente']->id_rol)
            ->and($user->id_cliente)->not->toBeNull();

        expect(Cliente::find($user->id_cliente))->not->toBeNull();
    });

    it('asigna SIEMPRE el rol Cliente sin importar otros datos', function () {
        $user = $this->service->registrarDesdePortal([
            'name'     => 'Pedro Admin',
            'email'    => 'pedro@mail.com',
            'password' => 'clave',
            'nombre'   => 'Pedro',
            'apellido' => 'Ramirez',
            'telefono' => '5553334444',
        ]);

        // Aunque el nombre diga "Admin", el rol debe ser Cliente
        expect($user->id_rol)->toBe($this->roles['cliente']->id_rol);
    });

    it('registro por recepción también asigna rol Cliente', function () {
        $recepcionista = crearUsuarioEmpleado($this->roles['recepcion'], 'rec@taller.com');

        $user = $this->service->registrarPorRecepcion([
            'nombre'   => 'Carlos',
            'apellido' => 'Ruiz',
            'email'    => 'carlos@mail.com',
            'password' => 'clave456',
            'telefono' => '5557778888',
        ], $recepcionista->id);

        expect($user->id_rol)->toBe($this->roles['cliente']->id_rol);
    });

    it('lanza excepción si el rol Cliente no existe en BD', function () {
        // Eliminar el rol Cliente para simular mala configuración
        $this->roles['cliente']->delete();

        expect(fn () => $this->service->registrarDesdePortal([
            'name'     => 'Sin Rol',
            'email'    => 'sinrol@mail.com',
            'password' => 'clave',
            'nombre'   => 'Sin',
            'apellido' => 'Rol',
            'telefono' => '0000000000',
        ]))->toThrow(ValidationException::class);
    });

    it('crea el cliente en la tabla clientes al registrarse', function () {
        $this->service->registrarDesdePortal([
            'name'     => 'Laura Test',
            'email'    => 'laura@test.com',
            'password' => 'pass',
            'nombre'   => 'Laura',
            'apellido' => 'Gómez',
            'telefono' => '5550001111',
        ]);

        expect(Cliente::where('nombre', 'Laura')->exists())->toBeTrue();
    });
});
