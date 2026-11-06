@extends('layouts.private')

@section('title', 'Recepción de Equipos')
@section('page-title', 'Módulo de Recepción e Intake')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    @if (session('error'))
        <div class="bg-rose-950/80 border border-rose-500/50 text-rose-300 p-4 rounded flex items-center gap-2 font-mono text-xs">
            <span class="material-symbols-outlined text-sm">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="noir-card bg-[#111318] p-8 border border-[#2d3038]">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#2d3038]">
            <span class="material-symbols-outlined text-[#ffb800] text-2xl">move_to_inbox</span>
            <div>
                <h2 class="text-lg font-bold text-white uppercase tracking-wider">
                    Registro de Cliente y Recepción de Hardware
                </h2>
                <p class="text-xs text-[#8a8e9b] font-mono">
                    Ingresa los datos del cliente, las fechas pactadas y la información técnica del dispositivo.
                </p>
            </div>
        </div>

        <form action="{{ route('recepcion.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs">
            @csrf

            <!-- SECCIÓN 1: DATOS DEL CLIENTE -->
            <div class="border-b border-[#2d3038] pb-6">
                <h3 class="text-[#ffb800] font-mono uppercase font-bold text-xs mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">person</span>
                    1. Información del Cliente
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Nombre Completo del Cliente</label>
                        <input type="text" name="cliente_name" value="{{ old('cliente_name') }}" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Correo Electrónico (Acceso Portal)</label>
                        <input type="email" name="cliente_email" value="{{ old('cliente_email') }}" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Teléfono de Contacto</label>
                        <input type="text" name="cliente_telefono" value="{{ old('cliente_telefono') }}" class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Contraseña del Portal Cliente</label>
                        <input type="password" name="cliente_password" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: DATOS DEL EQUIPO -->
            <div class="border-b border-[#2d3038] pb-6">
                <h3 class="text-[#ffb800] font-mono uppercase font-bold text-xs mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">laptop_mac</span>
                    2. Información del Dispositivo
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Tipo de Dispositivo</label>
                        <input type="text" name="tipo_equipo" value="{{ old('tipo_equipo') }}" placeholder="ej. Laptop, Desktop, Consola" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Marca</label>
                        <input type="text" name="marca" value="{{ old('marca') }}" placeholder="ej. Dell, HP, Lenovo, Asus" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Modelo</label>
                        <input type="text" name="modelo" value="{{ old('modelo') }}" placeholder="ej. Latitude 5420" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Número de Serie / IMEI</label>
                        <input type="text" name="numero_serie" value="{{ old('numero_serie') }}" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: DIAGNÓSTICO INICIAL, FECHAS & EVIDENCIA -->
            <div>
                <h3 class="text-[#ffb800] font-mono uppercase font-bold text-xs mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">assignment</span>
                    3. Compromiso de Entrega & Diagnóstico
                </h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-mono uppercase text-[#ffb800] mb-2">Fecha Deseada por Cliente</label>
                            <input type="date" name="fecha_deseada" min="{{ date('Y-m-d') }}" value="{{ old('fecha_deseada', date('Y-m-d')) }}" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-mono uppercase text-emerald-400 mb-2">Fecha Límite Oficial del Taller</label>
                            <input type="date" name="fecha_limite" min="{{ date('Y-m-d') }}" value="{{ old('fecha_limite', date('Y-m-d')) }}" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Tipo de Servicio Solicitado</label>
                        <select name="tipo_servicio" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">
                            <option value="Diagnóstico">Diagnóstico ($0)</option>
                            <option value="Reparación">Reparación Directa</option>
                            <option value="Mantenimiento">Mantenimiento Preventivo</option>
                            <option value="Garantía">Garantía</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-mono uppercase text-[#8a8e9b] mb-2">Falla Reportada por el Cliente</label>
                        <textarea name="falla_reportada" rows="3" required class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white font-mono focus:border-[#ffb800] focus:outline-none">{{ old('falla_reportada') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-[#2d3038] flex justify-end">
                <button type="submit" class="px-6 py-3 bg-[#ffb800] text-[#0d0f12] font-bold font-mono uppercase tracking-wider hover:bg-white transition-colors amber-glow">
                    Generar Orden e Imprimir Comprobante
                </button>
            </div>
        </form>
    </div>
</div>
@endsection