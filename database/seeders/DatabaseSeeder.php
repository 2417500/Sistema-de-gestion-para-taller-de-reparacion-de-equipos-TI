<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Herramienta;
use App\Models\Refaccion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear los 5 Roles del sistema
        $adminRole     = Role::firstOrCreate(['nombre' => 'Administrador'], ['descripcion' => 'Aprobaciones y control total']);
        $recepcionRole = Role::firstOrCreate(['nombre' => 'Recepcion'], ['descripcion' => 'Recepción de equipos y atención']);
        $tecnicoRole   = Role::firstOrCreate(['nombre' => 'Tecnico'], ['descripcion' => 'Diagnóstico y reparación']);
        $almacenRole   = Role::firstOrCreate(['nombre' => 'Almacen'], ['descripcion' => 'Gestión de refacciones y herramientas']);
        $clienteRole   = Role::firstOrCreate(['nombre' => 'Cliente'], ['descripcion' => 'Consulta de estados y cotizaciones']);

        $password = Hash::make('password123');

        // 2. Administrador
        User::updateOrCreate(
            ['email' => 'admin@soportec.com'],
            [
                'name' => 'Carlos Administrador',
                'telefono' => '5551112233',
                'role_id' => $adminRole->id,
                'password' => $password,
            ]
        );

        // 3. Recepción
        User::updateOrCreate(
            ['email' => 'recepcion@soportec.com'],
            [
                'name' => 'Ana Recepción',
                'telefono' => '5552223344',
                'role_id' => $recepcionRole->id,
                'password' => $password,
            ]
        );

        // 4. Técnico
        User::updateOrCreate(
            ['email' => 'tecnico@soportec.com'],
            [
                'name' => 'Jesús Técnico',
                'telefono' => '5553334455',
                'role_id' => $tecnicoRole->id,
                'password' => $password,
            ]
        );

        // 5. Almacén
        User::updateOrCreate(
            ['email' => 'almacen@soportec.com'],
            [
                'name' => 'Roberto Almacén',
                'telefono' => '5554445566',
                'role_id' => $almacenRole->id,
                'password' => $password,
            ]
        );

        // 6. Cliente
        $userCliente = User::updateOrCreate(
            ['email' => 'cliente@soportec.com'],
            [
                'name' => 'Juan Cliente',
                'telefono' => '5555556677',
                'role_id' => $clienteRole->id,
                'password' => $password,
            ]
        );

        Cliente::firstOrCreate(
            ['user_id' => $userCliente->id],
            [
                'equipos_reparados_count' => 0,
                'aplica_descuento' => false,
            ]
        );

        // 7. Herramientas de prueba
        Herramienta::firstOrCreate(
            ['numero_serie' => 'HERR-001'],
            [
                'nombre' => 'Kit de Desarmadores de Precisión',
                'marca' => 'iFixit',
                'modelo' => 'Pro Tech',
                'stock_total' => 5,
                'stock_disponible' => 5,
            ]
        );

        Herramienta::firstOrCreate(
            ['numero_serie' => 'HERR-002'],
            [
                'nombre' => 'Pasta Térmica',
                'marca' => 'Arctic',
                'modelo' => 'MX-4',
                'stock_total' => 10,
                'stock_disponible' => 10,
            ]
        );

        // 8. Refacciones de prueba
        Refaccion::firstOrCreate(
            ['nombre' => 'Pantalla LED 15.6 pulgadas'],
            [
                'marca' => 'Generic',
                'modelo' => 'FHD 1080p',
                'precio_unitario' => 1200.00,
                'stock_disponible' => 8,
            ]
        );
    }
}