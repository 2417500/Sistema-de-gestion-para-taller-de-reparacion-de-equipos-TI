@extends('layouts.private')

@section('title', 'Dashboard')
@section('page-title', 'Workbench de Control')

@section('content')
<div class="space-y-6 font-mono text-xs">

    @if (session('error'))
        <div class="bg-rose-950/80 border border-rose-500/50 text-rose-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- TARJETAS DE INDICADORES -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="noir-card bg-[#111318] p-4 border border-[#2d3038] space-y-2">
            <div class="flex justify-between items-center text-[#8a8e9b]">
                <span class="uppercase font-bold">COLA EN RECEPCIÓN</span>
                <span class="material-symbols-outlined text-base">inbox</span>
            </div>
            <div class="text-2xl font-bold text-white">
                {{ $ordenes->filter(fn($o) => strtolower($o->estado ?? '') === 'pendiente')->count() }}
            </div>
            <p class="text-[10px] text-[#8a8e9b]">Equipos pendientes de diagnóstico inicial</p>
        </div>

        <div class="noir-card bg-[#111318] p-4 border border-[#2d3038] space-y-2">
            <div class="flex justify-between items-center text-[#ffb800]">
                <span class="uppercase font-bold">EN BANCO TÉCNICO</span>
                <span class="material-symbols-outlined text-base">precision_manufacturing</span>
            </div>
            <div class="text-2xl font-bold text-[#ffb800]">
                {{ $ordenes->filter(fn($o) => strtolower($o->estado ?? '') === 'en_proceso')->count() }}
            </div>
            <p class="text-[10px] text-[#8a8e9b]">Pruebas eléctricas y reemplazo activo</p>
        </div>

        <div class="noir-card bg-[#111318] p-4 border border-[#2d3038] space-y-2">
            <div class="flex justify-between items-center text-emerald-400">
                <span class="uppercase font-bold">LISTO PARA ENTREGA</span>
                <span class="material-symbols-outlined text-base">task_alt</span>
            </div>
            <div class="text-2xl font-bold text-emerald-400">
                {{ $ordenes->filter(fn($o) => strtolower($o->estado ?? '') === 'terminado')->count() }}
            </div>
            <p class="text-[10px] text-[#8a8e9b]">Certificación de control de calidad QA</p>
        </div>

        <div class="noir-card bg-[#111318] p-4 border border-[#2d3038] space-y-2">
            <div class="flex justify-between items-center text-sky-400">
                <span class="uppercase font-bold">APROBACIÓN ADMIN</span>
                <span class="material-symbols-outlined text-base">admin_panel_settings</span>
            </div>
            <div class="text-2xl font-bold text-sky-400">
                {{ $ordenes->where('requiere_aprobacion_admin', 1)->count() }}
            </div>
            <p class="text-[10px] text-[#8a8e9b]">Exceso refacciones o herramientas</p>
        </div>
    </div>

    <!-- TABLA PRINCIPAL DE ÓRDENES -->
    <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4">
        <div class="flex justify-between items-center pb-4 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb800]">list_alt</span>
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">COLA ACTIVA DE ÓRDENES DE SERVICIO</h2>
            </div>
            <span class="text-[10px] text-[#8a8e9b] uppercase">MATRIZ TÉCNICA // PRIORIDAD DE BANCO</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#182238] text-white uppercase text-[10px] border-b border-[#2d3038]">
                        <th class="p-3 font-semibold tracking-wider">CÓDIGO SERVICIO</th>
                        <th class="p-3 font-semibold tracking-wider">EQUIPO / MODELO</th>
                        <th class="p-3 font-semibold tracking-wider">N° SERIE</th>
                        <th class="p-3 font-semibold tracking-wider">CLIENTE</th>
                        <th class="p-3 font-semibold tracking-wider">TÉCNICO</th>
                        <th class="p-3 font-semibold tracking-wider">ESTADO</th>
                        <th class="p-3 font-semibold tracking-wider">ESTADO ORDEN</th>
                        <th class="p-3 font-semibold tracking-wider text-right">ACCIÓN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3038] text-white">
                    @forelse ($ordenes as $orden)
                        <tr class="hover:bg-[#16181f]/50 transition-colors">
                            <td class="p-3 text-[#ffb800] font-bold">{{ $orden->codigo_servicio }}</td>
                            <td class="p-3 uppercase">{{ $orden->marca }} {{ $orden->modelo }}</td>
                            <td class="p-3 text-[#8a8e9b] uppercase">{{ $orden->numero_serie ?? 'SIN REGISTRO' }}</td>
                            <td class="p-3">{{ $orden->cliente_nombre ?? 'Cliente Desconocido' }}</td>
                            <td class="p-3 text-[#8a8e9b]">{{ $orden->tecnico_nombre ?? 'Por asignar' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 text-[10px] font-bold border rounded uppercase bg-[#1d212b] border-[#ffb800]/40 text-[#ffb800]">
                                    {{ $orden->estado }}
                                </span>
                            </td>

                            <!-- COLUMNA ESTADO ORDEN (NORMAL / URGENTE) -->
                            <td class="p-3">
                                @if(strtoupper($orden->prioridad ?? 'NORMAL') === 'URGENTE')
                                    <span class="px-2 py-0.5 text-[10px] font-bold border rounded uppercase bg-rose-950/80 border-rose-500 text-rose-400">
                                        URGENTE
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-[10px] font-bold border rounded uppercase bg-emerald-950/80 border-emerald-500 text-emerald-400">
                                        NORMAL
                                    </span>
                                @endif
                            </td>

                            <!-- ACCIONES VER ORDEN / ATENDER ORDEN CON BLOQUEO -->
                            <td class="p-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Botón Ver Orden -->
                                    <a href="{{ route('ordenes.show', $orden->id) }}" class="px-3 py-1 bg-[#182238] border border-[#2d3038] text-white font-bold uppercase rounded hover:border-[#ffb800] transition-colors inline-block text-[10px]">
                                        VER ORDEN
                                    </a>

                                    <!-- Botón Atender Orden -->
                                    @if(empty($orden->tecnico_id))
                                        <form action="{{ route('ordenes.atender', $orden->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-[#ffb800] text-[#0d0f12] font-bold uppercase rounded hover:bg-white transition-colors text-[10px]">
                                                ATENDER ORDEN
                                            </button>
                                        </form>
                                    @elseif($orden->tecnico_id == Auth::id())
                                        <span class="px-2 py-1 bg-sky-500/20 border border-sky-400 text-sky-300 font-bold uppercase rounded text-[10px]">
                                            EN ATENCIÓN
                                        </span>
                                    @else
                                        <button disabled class="px-3 py-1 bg-[#16181f] border border-[#2d3038] text-[#8a8e9b] font-bold uppercase rounded text-[10px] cursor-not-allowed">
                                            ATENDIDO POR: {{ $orden->tecnico_nombre }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-[#8a8e9b] uppercase">No hay órdenes registradas en el sistema.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection