<?php

use App\Models\Role;
use App\Models\User;

test('vista publica index renderiza correctamente e incluye soporte de modo claro/oscuro', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('TecnoFix Solutions');
    $response->assertSee('SoporTec v4.2');
    $response->assertSee('toggleTheme()', false);
    $response->assertSee("localStorage.getItem('theme')", false);
    $response->assertSee('theme-toggle-icon', false);
    $response->assertSee('html:not(.dark)', false);
});

test('vista privada dashboard renderiza correctamente para usuario autenticado e incluye soporte de modo claro/oscuro', function () {
    $role = Role::create(['nombre' => 'Administrador', 'descripcion' => 'Admin']);
    $user = User::create([
        'role_id' => $role->id,
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee('Tablero Técnico de Reparaciones');
    $response->assertSee('Cola Activa de Órdenes de Servicio');
    $response->assertSee('toggleTheme()', false);
    $response->assertSee("localStorage.getItem('theme')", false);
    $response->assertSee('theme-toggle-icon', false);
    $response->assertSee('html:not(.dark)', false);
});

test('vista guest login incluye soporte de modo claro/oscuro', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
    $response->assertSee('toggleTheme()', false);
    $response->assertSee("localStorage.getItem('theme')", false);
    $response->assertSee('theme-toggle-icon', false);
    $response->assertSee('html:not(.dark)', false);
});
