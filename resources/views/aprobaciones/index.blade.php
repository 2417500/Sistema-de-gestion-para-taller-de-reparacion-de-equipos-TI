@extends('layouts.private')

@section('title', 'Aprobaciones Admin')
@section('page-title', 'Aprobación de Salida de Materiales y Herramientas')

@section('content')
<div class="space-y-6 font-mono text-xs">
    @if (session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4">
        <div class="flex justify-between items-center pb-2 border-b border-[#2d3038]">
            <h2 class="text-sm font-bold text-[#ffb800] uppercase">SOLICITUDES DE SOLICITUD DE MATERIALES</h2>
            <span class="text-[10px] text-[#8a8e9b]">PANEL CONTROL DE STOCK & HERRAMIENTAS</span>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#182238] text-white uppercase text-[10px]">
                    <th class="p-3">ORDEN</th>
                    <th class="p-3">TÉCNICO</th>
                    <th class="p-3">SOLICITADO</th>
                    <th class="p-3">CANTIDAD</th>
                    <th class="p-3">ESTADO</th>
                    <th class="p-3 text-right">ACCIÓN ADMIN</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#2d3038] text-white">
                @forelse($solicitudes as $sol)
                    <tr>
                        <td class="p-3 text-[#ffb800] font-bold">{{ $sol->codigo_servicio }}</td>
                        <td class="p-3">{{ $sol->tecnico_nombre }}</td>
                        <td class="p-3">{{ $sol->item_nombre }}</td>
                        <td class="p-3">{{ $sol->cantidad }} pzs</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-[10px] bg-[#1d212b] border border-[#ffb800]/40 text-[#ffb800] font-bold">
                                {{ $sol->estado }}
                            </span>
                        </td>
                        <td class="p-3 text-right space-x-2">
                            @if($sol->estado === 'PENDIENTE')
                                <form action="{{ url('/aprobaciones/aprobar/' . $sol->orden_id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded text-[10px] uppercase transition-colors">
                                        APROBAR
                                    </button>
                                </form>
                                <form action="{{ url('/aprobaciones/rechazar/' . $sol->orden_id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded text-[10px] uppercase transition-colors">
                                        RECHAZAR
                                    </button>
                                </form>
                            @else
                                <span class="text-[#8a8e9b] text-[10px] italic uppercase">PROCESADO</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-[#8a8e9b]">No hay peticiones de stock pendientes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection