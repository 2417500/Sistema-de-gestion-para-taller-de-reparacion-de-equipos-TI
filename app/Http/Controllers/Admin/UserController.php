<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        // Solo obtener roles de personal (excluyendo Cliente)
        $roles = Role::whereIn('nombre', ['Recepcion', 'Tecnico', 'Almacen'])->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telefono' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
            'activo' => 1,
        ]);

        return redirect()->route('admin.personal')->with('success', 'Usuario de personal creado exitosamente.');
    }

    // LISTAR EMPLEADOS PARA EL PANEL DE CUENTAS ACTIVAS
    public function personal()
    {
        $empleados = User::whereHas('role', function($q) {
            $q->whereIn('nombre', ['Recepcion', 'Tecnico', 'Almacen']);
        })->with('role')->get();

        return view('admin.personal', compact('empleados'));
    }

    // INTERRUPTOR DE ESTADO (ACTIVAR / DAR DE BAJA)
    public function toggleEstado($id)
    {
        $empleado = User::findOrFail($id);
        
        // Alterna entre activo (1) e inactivo (0)
        $empleado->activo = !$empleado->activo;
        $empleado->save();

        $estadoTexto = $empleado->activo ? 'ACTIVADA' : 'DADA DE BAJA';

        return back()->with('success', "La cuenta del empleado {$empleado->name} ha sido {$estadoTexto} correctamente.");
    }
}