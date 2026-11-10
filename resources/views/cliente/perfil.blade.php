@extends('layouts.private')

@section('title', 'Mi Perfil')
@section('page-title', 'Perfil de Usuario Cliente')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 font-mono text-xs">
    @if (session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="noir-card bg-[#111318] p-8 border border-[#2d3038]">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#2d3038]">
            <span class="material-symbols-outlined text-[#ffb800] text-2xl">account_circle</span>
            <div>
                <h2 class="text-lg font-bold text-white uppercase tracking-wider">
                    Mi Perfil
                </h2>
                <p class="text-xs text-[#8a8e9b]">
                    Consulta tus datos de registro y actualiza tu información personal.
                </p>
            </div>
        </div>

        <form action="{{ route('cliente.perfil.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Fecha de Registro en el Sistema</label>
                <input type="text" value="{{ Auth::user()->created_at ? Auth::user()->created_at->format('d/m/Y H:i') : 'N/A' }}" readonly class="w-full bg-[#182238] border border-[#2d3038] rounded p-3 text-[#ffb800] font-mono cursor-not-allowed">
            </div>

            <div>
                <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Nombre Completo / Usuario</label>
                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
            </div>

            <div>
                <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
            </div>

            <div>
                <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Número Telefónico</label>
                <input type="text" name="telefono" value="{{ old('telefono', Auth::user()->cliente->telefono ?? Auth::user()->telefono ?? '') }}" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
            </div>

            <div class="pt-4 border-t border-[#2d3038] flex justify-end">
                <button type="submit" class="px-6 py-3 bg-[#ffb800] text-[#0d0f12] font-bold font-mono uppercase tracking-wider hover:bg-white transition-colors">
                    Actualizar Mis Datos
                </button>
            </div>
        </form>
    </div>
</div>
@endsection