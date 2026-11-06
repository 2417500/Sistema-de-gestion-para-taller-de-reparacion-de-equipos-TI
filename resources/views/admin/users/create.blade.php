@extends('layouts.private')

@section('title', 'Alta de Empleados')
@section('page-title', 'Gestión de Personal de Taller')
@section('breadcrumb', 'Alta de Nuevo Operador')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="noir-card bg-surface-container-lowest p-8 border border-outline-variant">
        
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-outline-variant">
            <span class="material-symbols-outlined text-primary-container text-2xl">person_add</span>
            <div>
                <h2 class="text-headline-sm font-bold text-white uppercase tracking-wider">
                    Alta de Nuevo Empleado (Personal de Taller)
                </h2>
                <p class="text-xs text-on-surface-variant font-mono">
                    Crea credenciales operativas para Recepción, Técnico o Almacén.
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded mb-6 font-mono text-xs flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-950/80 border border-red-500/50 text-red-300 p-4 rounded mb-6 font-mono text-xs">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <div>
                <label class="block font-mono uppercase text-on-surface-variant mb-2">Nombre Completo del Empleado</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       class="w-full bg-surface-container-low border border-outline-variant rounded p-3 text-white font-mono focus:border-primary-container focus:outline-none">
            </div>

            <div>
                <label class="block font-mono uppercase text-on-surface-variant mb-2">Correo Electrónico Institucional</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       class="w-full bg-surface-container-low border border-outline-variant rounded p-3 text-white font-mono focus:border-primary-container focus:outline-none">
            </div>

            <div>
                <label class="block font-mono uppercase text-on-surface-variant mb-2">Teléfono de Contacto</label>
                <input type="text" name="telefono" value="{{ old('telefono') }}" 
                       class="w-full bg-surface-container-low border border-outline-variant rounded p-3 text-white font-mono focus:border-primary-container focus:outline-none">
            </div>

            <div>
                <label class="block font-mono uppercase text-on-surface-variant mb-2">Rol Operativo Asignado</label>
                <select name="role_id" required 
                        class="w-full bg-surface-container-low border border-outline-variant rounded p-3 text-white font-mono focus:border-primary-container focus:outline-none">
                    <option value="">-- SELECCIONAR ROL --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ strtoupper($role->nombre) }} — {{ $role->descripcion }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-mono uppercase text-on-surface-variant mb-2">Contraseña Temporal</label>
                    <input type="password" name="password" required 
                           class="w-full bg-surface-container-low border border-outline-variant rounded p-3 text-white font-mono focus:border-primary-container focus:outline-none">
                </div>
                <div>
                    <label class="block font-mono uppercase text-on-surface-variant mb-2">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" required 
                           class="w-full bg-surface-container-low border border-outline-variant rounded p-3 text-white font-mono focus:border-primary-container focus:outline-none">
                </div>
            </div>

            <div class="pt-4 border-t border-outline-variant/40 flex justify-end gap-3">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-surface-container border border-outline-variant text-on-surface font-mono uppercase hover:bg-surface-container-high transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-primary-container text-noir-black font-bold font-mono uppercase tracking-wider hover:bg-white transition-colors amber-glow">
                    Registrar Empleado
                </button>
            </div>
        </form>
    </div>
</div>
@endsection