@extends('layouts.private')

@section('title', 'Cotizaciones')
@section('page-title', 'Módulo de Cotizaciones & Presupuestos')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 font-mono text-xs">
    
    @if (session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-950/80 border border-red-500/50 text-red-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">warning</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038]">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#2d3038]">
            <span class="material-symbols-outlined text-[#ffb800] text-2xl">request_quote</span>
            <div>
                <h2 class="text-lg font-bold text-white uppercase tracking-wider">
                    Historial de Cotizaciones y Estados de Pago
                </h2>
                <p class="text-xs text-[#8a8e9b]">
                    Revisión de presupuestos de reparación, diagnóstico y decisiones del cliente.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#2d3038] text-[#8a8e9b] uppercase">
                        <th class="p-3">Código</th>
                        <th class="p-3">Dispositivo</th>
                        <th class="p-3">Diagnóstico</th>
                        <th class="p-3">Total ($)</th>
                        <th class="p-3">Anticipo (50%)</th>
                        <th class="p-3">Estado</th>
                        <th class="p-3 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3038] text-white">
                    @forelse ($cotizaciones as $cot)
                        <tr class="hover:bg-[#16181f]/50 transition-colors">
                            <td class="p-3 font-bold text-[#ffb800]">{{ $cot->codigo_servicio }}</td>
                            <td class="p-3">{{ $cot->marca }} {{ $cot->modelo }}</td>
                            <td class="p-3 max-w-xs truncate text-[#c4c6cf]">{{ $cot->diagnostico ?? 'En revisión' }}</td>
                            <td class="p-3 font-bold text-white">${{ number_format($cot->costo_total ?? 0, 2) }}</td>
                            <td class="p-3 text-emerald-400 font-bold">${{ number_format(($cot->costo_total ?? 0) * 0.5, 2) }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 bg-[#1d212b] border border-[#ffb800]/30 text-[#ffb800] rounded uppercase text-[10px] font-bold">
                                    {{ str_replace('_', ' ', $cot->estado) }}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if(in_array(strtoupper($cot->estado), ['TERMINADO', 'EN_PROCESO', 'COTIZACION_ENVIADA', 'PENDIENTE']))
                                        <a href="{{ route('cotizaciones.show', $cot->id) }}" 
                                           class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded transition-colors inline-block text-[10px] uppercase">
                                            ENTREGAR Y LIQUIDAR
                                        </a>
                                    @elseif(strtoupper($cot->estado) === 'ENTREGADO')
                                        <a href="{{ route('cotizaciones.show', $cot->id) }}" 
                                           class="px-2.5 py-1.5 bg-[#1d212b] text-[#8a8e9b] hover:text-white border border-[#2d3038] font-bold rounded transition-colors text-[10px] uppercase">
                                            VER DETALLE
                                        </a>
                                        <a href="{{ route('entrega.pdf', $cot->id) }}" 
                                           class="px-3 py-1.5 bg-[#ffb800] text-[#0d0f12] hover:bg-white font-bold rounded transition-colors inline-block text-[10px] uppercase">
                                            📄 PDF ENTREGA
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-[#8a8e9b]">
                                No se encontraron cotizaciones registradas en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection