<?php

namespace App\Http\Controllers\Recepcion;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telefono' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        $clienteRole = Role::where('nombre', 'Cliente')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'role_id' => $clienteRole->id,
            'password' => Hash::make($request->password),
        ]);

        Cliente::create([
            'user_id' => $user->id,
            'equipos_reparados_count' => 0,
            'aplica_descuento' => false,
        ]);

        return redirect()->back()->with('success', 'Cliente y cuenta de acceso creados correctamente.');
    }
}