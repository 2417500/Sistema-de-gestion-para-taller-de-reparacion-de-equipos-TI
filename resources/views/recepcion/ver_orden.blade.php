@extends('layouts.private')

@section('title', 'Detalle de Orden')
@section('page-title', 'Expediente de Servicio - ' . ($orden->codigo_servicio ?? 'ST-2026'))

@section('content')
<div class="max-w-5xl mx-auto space-y-6 font-mono text-xs">

    <!-- BOTÓN REGRESAR Y ESTATUS -->
    <div class="flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-[#181a20] border border-[#2d3038] text-white rounded hover:bg-[#252830] transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>VOLVER AL DASHBOARD</span>
        </a>

        <div class="flex items-center gap-2">
            <span class="text-[#8a8e9b] font-bold uppercase">ESTADO DE ORDEN:</span>
            <span class="px-3 py-1 bg-amber-950/80 border border-[#ffb800]/50 text-[#ffb800] font-extrabold rounded uppercase">
                {{ $orden->estado ?? 'Pendiente' }}
            </span>
        </div>
    </div>

    <!-- 1. INFORMACIÓN DEL CLIENTE & TICKET -->
    <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4 rounded">
        <div class="flex items-center justify-between pb-3 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb800]">person</span>
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">DATOS DEL CLIENTE Y REGISTRO</h2>
            </div>
            <span class="text-[#ffb800] font-bold text-sm">{{ $orden->codigo_servicio }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-white">
            <div>
                <span class="block text-[#8a8e9b] uppercase text-[10px] font-bold">Cliente</span>
                <p class="text-sm font-bold">{{ $orden->cliente_nombre ?? 'Sin Registro' }}</p>
            </div>
            <div>
                <span class="block text-[#8a8e9b] uppercase text-[10px] font-bold">Correo Electrónico</span>
                <p class="text-sm font-bold">{{ $orden->cliente_email ?? 'N/A' }}</p>
            </div>
            <div>
                <span class="block text-[#8a8e9b] uppercase text-[10px] font-bold">Teléfono Contacto</span>
                <p class="text-sm font-bold">{{ $orden->cliente_telefono ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- 2. HARDWARE & SERVICIO -->
    <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4 rounded">
        <div class="flex items-center gap-2 pb-3 border-b border-[#2d3038]">
            <span class="material-symbols-outlined text-[#ffb800]">laptop_mac</span>
            <h2 class="text-sm font-bold text-white uppercase tracking-wider">HARDWARE & PRIORIDAD</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-white">
            <div>
                <span class="block text-[#8a8e9b] uppercase text-[10px] font-bold">Tipo Equipo</span>
                <p class="font-bold">{{ $orden->tipo_equipo ?? 'N/A' }}</p>
            </div>
            <div>
                <span class="block text-[#8a8e9b] uppercase text-[10px] font-bold">Marca / Modelo</span>
                <p class="font-bold">{{ $orden->marca ?? '' }} {{ $orden->modelo ?? '' }}</p>
            </div>
            <div>
                <span class="block text-[#8a8e9b] uppercase text-[10px] font-bold">Número de Serie / IMEI</span>
                <p class="font-bold text-[#ffb800]">{{ $orden->numero_serie ?? 'N/A' }}</p>
            </div>
            <div>
                <span class="block text-[#8a8e9b] uppercase text-[10px] font-bold">Prioridad</span>
                <p class="font-bold uppercase text-amber-400">{{ $orden->prioridad ?? 'NORMAL' }}</p>
            </div>
        </div>

        <div class="pt-3 border-t border-[#2d3038]/60">
            <span class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Falla Reportada & Observaciones</span>
            <div class="bg-[#181a20] border border-[#2d3038] p-4 text-white rounded whitespace-pre-line leading-relaxed">
                {{ $orden->falla_reportada ?? 'Sin reporte inicial.' }}
            </div>
        </div>
    </div>

    <!-- 3. EVIDENCIA FOTOGRÁFICA DE RECEPCIÓN -->
    @php
        $fotosRaw = json_decode($orden->fotos_recepcion ?? '[]', true);
        $fotosEquipo = [];
        $fotosExtras = [];

        if (is_array($fotosRaw)) {
            if (isset($fotosRaw['equipo']) && is_array($fotosRaw['equipo'])) {
                $fotosEquipo = $fotosRaw['equipo'];
            }
            if (isset($fotosRaw['extras']) && is_array($fotosRaw['extras'])) {
                $fotosExtras = $fotosRaw['extras'];
            }
            if (empty($fotosEquipo) && empty($fotosExtras)) {
                array_walk_recursive($fotosRaw, function($val) use (&$fotosEquipo) {
                    if (is_string($val)) $fotosEquipo[] = $val;
                });
            }
        }
    @endphp

    @if(count($fotosEquipo) > 0 || count($fotosExtras) > 0)
        <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4 rounded">
            <div class="flex items-center gap-2 pb-3 border-b border-[#2d3038]">
                <span class="material-symbols-outlined text-[#ffb800]">photo_camera</span>
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">EVIDENCIA FOTOGRÁFICA DE ENTRADA</h2>
            </div>

            @if(count($fotosEquipo) > 0)
                <div class="space-y-2">
                    <span class="text-white font-bold uppercase text-[11px] block">Fotos del Equipo:</span>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($fotosEquipo as $foto)
                            <div class="border border-[#2d3038] p-1 bg-[#181a20] rounded">
                                <img src="{{ asset('storage/' . trim(str_replace('\\', '/', $foto))) }}" class="w-full h-32 object-cover rounded">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(count($fotosExtras) > 0)
                <div class="space-y-2 pt-2">
                    <span class="text-white font-bold uppercase text-[11px] block">Accesorios / Extras:</span>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($fotosExtras as $foto)
                            <div class="border border-[#2d3038] p-1 bg-[#181a20] rounded">
                                <img src="{{ asset('storage/' . trim(str_replace('\\', '/', $foto))) }}" class="w-full h-32 object-cover rounded">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- 4. ACCIONES DEL TÉCNICO -->
    <div class="flex justify-end gap-4 pt-2">
        @if(!$orden->tecnico_id)
            <form action="{{ route('ordenes.atender', $orden->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 bg-[#ffb800] text-[#0d0f12] font-extrabold uppercase rounded hover:bg-white transition-colors flex items-center gap-2 shadow-lg">
                    <span class="material-symbols-outlined text-base">build</span>
                    <span>ATENDER ESTA ORDEN</span>
                </button>
            </form>
        @else
            <a href="{{ route('banco.diagnosticar', $orden->id) }}" class="px-6 py-3 bg-[#ffb800] text-[#0d0f12] font-extrabold uppercase rounded hover:bg-white transition-colors flex items-center gap-2 shadow-lg">
                <span class="material-symbols-outlined text-base">handyman</span>
                <span>IR A BANCO Y DIAGNOSTICAR</span>
            </a>
        @endif
    </div>

</div>
@endsection