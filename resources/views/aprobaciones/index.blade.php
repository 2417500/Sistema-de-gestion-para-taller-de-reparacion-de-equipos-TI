@extends('layouts.private')

@section('title', 'Aprobaciones Admin')
@section('page-title', 'Aprobaciones de Solicitudes')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 font-mono text-xs">

    @if (session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- TABLA 1: PETICIONES DE HERRAMIENTAS PENDIENTES DE APROBACIÓN -->
    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038] space-y-4">
        <div class="flex justify-between items-center pb-4 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb800]">handyman</span>
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">APROBACIÓN DE HERRAMIENTAS (Peticiones Técnicos)</h2>
            </div>
            <span class="text-[10px] text-[#ffb800] bg-[#1d212b] px-2 py-0.5 border border-[#ffb800]/30 font-bold uppercase">Módulo Herramientas</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#182238] text-white uppercase text-[10px] border-b border-[#2d3038]">
                        <th class="p-3">TÉCNICO</th>
                        <th class="p-3">HERRAMIENTA SOLICITADA</th>
                        <th class="p-3">CANTIDAD</th>
                        <th class="p-3">ESTADO</th>
                        <th class="p-3 text-right">ACCIÓN ADMIN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3038] text-white">
                    @forelse ($herramientasPendientes as $h)
                        <tr class="hover:bg-[#16181f]/50 transition-colors">
                            <td class="p-3 font-bold uppercase text-white">{{ $h->tecnico_nombre }}</td>
                            <td class="p-3 text-[#ffb800] font-bold">{{ $h->herramienta_nombre }}</td>
                            <td class="p-3 font-bold">{{ $h->cantidad }} pza(s)</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 bg-amber-950/60 border border-amber-500/50 text-amber-400 text-[10px] uppercase font-bold rounded">
                                    PENDIENTE APROBACIÓN
                                </span>
                            </td>
                            <td class="p-3 text-right flex justify-end gap-2">
                                <form action="{{ route('aprobaciones.herramientas.aprobar', $h->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-emerald-600 text-white font-bold uppercase rounded hover:bg-emerald-500 transition-colors text-[10px]">
                                        ✓ APROBAR
                                    </button>
                                </form>
                                <form action="{{ route('aprobaciones.herramientas.rechazar', $h->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-rose-600 text-white font-bold uppercase rounded hover:bg-rose-500 transition-colors text-[10px]">
                                        ✕ RECHAZAR
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-[#8a8e9b] uppercase">No hay peticiones de herramientas pendientes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABLA 2: SOLICITUDES DE REFACCIONES PARA SERVICIOS/ÓRDENES -->
    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038] space-y-4">
        <div class="flex justify-between items-center pb-4 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb800]">inventory_2</span>
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">SOLICITUD DE MATERIALES & REFACCIONES (ÓRDENES)</h2>
            </div>
            <span class="text-[10px] text-[#8a8e9b] uppercase">Panel Control de Stock</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#182238] text-white uppercase text-[10px] border-b border-[#2d3038]">
                        <th class="p-3">ORDEN</th>
                        <th class="p-3">TÉCNICO</th>
                        <th class="p-3">SOLICITADO</th>
                        <th class="p-3">CANTIDAD</th>
                        <th class="p-3">ESTADO</th>
                        <th class="p-3 text-right">ACCIÓN ADMIN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3038] text-white">
                    @forelse ($solicitudes as $sol)
                        <tr class="hover:bg-[#16181f]/50 transition-colors">
                            <td class="p-3 text-[#ffb800] font-bold">{{ $sol->codigo_servicio }}</td>
                            <td class="p-3 font-bold uppercase">{{ $sol->tecnico_nombre }}</td>
                            <td class="p-3 max-w-xs truncate text-white font-bold">{{ $sol->item_nombre }}</td>
                            <td class="p-3 font-bold">{{ $sol->cantidad }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 bg-amber-950/60 border border-amber-500/50 text-amber-400 text-[10px] uppercase font-bold rounded">
                                    {{ $sol->estado }}
                                </span>
                            </td>
                            <td class="p-3 text-right flex justify-end gap-2">
                                <form action="{{ route('aprobaciones.aprobar', $sol->orden_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-emerald-600 text-white font-bold uppercase rounded hover:bg-emerald-500 transition-colors text-[10px]">
                                        ✓ APROBAR
                                    </button>
                                </form>
                                <form action="{{ route('aprobaciones.rechazar', $sol->orden_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-rose-600 text-white font-bold uppercase rounded hover:bg-rose-500 transition-colors text-[10px]">
                                        ✕ RECHAZAR
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-[#8a8e9b] uppercase">No hay peticiones de stock pendientes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection