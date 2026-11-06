@extends('layouts.private')

@section('title', 'Banco Técnico')
@section('page-title', 'Banco Técnico de Reparaciones')

@section('content')
<div class="max-w-6xl mx-auto font-mono text-xs space-y-6">

    @if (session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038] space-y-4">
        <div class="flex justify-between items-center pb-4 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb800]">build</span>
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">PANEL DE DIAGNÓSTICO & TRABAJO TÉCNICO</h2>
            </div>
            <span class="text-[10px] text-[#8a8e9b] uppercase">Gestión de equipos asignados en banco técnico.</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#182238] text-white uppercase text-[10px] border-b border-[#2d3038]">
                        <th class="p-3">CÓDIGO</th>
                        <th class="p-3">EQUIPO</th>
                        <th class="p-3">N° SERIE</th>
                        <th class="p-3">FALLA REPORTADA</th>
                        <th class="p-3">ESTADO</th>
                        <th class="p-3 text-right">ACCIÓN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3038] text-white">
                    @forelse ($ordenes as $orden)
                        <tr class="hover:bg-[#16181f]/50 transition-colors">
                            <td class="p-3 text-[#ffb800] font-bold">{{ $orden->codigo_servicio }}</td>
                            <td class="p-3 uppercase">{{ $orden->marca }} {{ $orden->modelo }}</td>
                            <td class="p-3 text-[#8a8e9b] uppercase">{{ $orden->numero_serie }}</td>
                            <td class="p-3 max-w-xs truncate text-[#8a8e9b]">{{ $orden->falla_reportada }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 text-[10px] font-bold border rounded uppercase bg-[#1d212b] border-[#ffb800]/40 text-[#ffb800]">
                                    {{ $orden->estado }}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                @if(in_array(strtolower($orden->estado), ['pendiente', 'en_diagnostico']))
                                    <a href="{{ route('banco.diagnosticar', $orden->id) }}" 
                                       class="px-3 py-1 bg-[#ffb800] text-[#0d0f12] font-bold uppercase rounded hover:bg-[#e0a200] transition-colors inline-block text-[10px]">
                                        Diagnosticar
                                    </a>
                                @elseif(strtolower($orden->estado) === 'en_proceso')
                                    <form action="{{ route('banco.finalizar', $orden->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-3 py-1 bg-emerald-600 text-white font-bold uppercase rounded hover:bg-emerald-500 transition-colors inline-block text-[10px]">
                                            ✓ Finalizar Reparación
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[#8a8e9b] text-[10px] uppercase font-bold">{{ $orden->estado }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-[#8a8e9b] uppercase">No hay ordenes asignadas en el banco técnico.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection