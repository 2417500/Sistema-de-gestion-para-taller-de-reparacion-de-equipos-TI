<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Verificar si ya existe el Administrador único
        $adminRole = Role::where('nombre', 'Administrador')->first();
        $adminExiste = User::where('role_id', $adminRole->id)->exists();

        if ($adminExiste) {
            return redirect()->back()->withErrors([
                'email' => 'Ya existe una cuenta de Administrador registrada en el sistema.'
            ]);
        }

        // Si no existe, crea el único Administrador
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $adminRole->id,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}