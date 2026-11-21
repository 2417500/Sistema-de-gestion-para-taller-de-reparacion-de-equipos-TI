@extends('layouts.private')

@section('title', 'Gestion de Garantias')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 font-mono text-xs">

    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/40 text-emerald-400 p-4 rounded-lg flex items-center gap-3">
            <span>OK: {{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-500/10 border border-rose-500/40 text-rose-400 p-4 rounded-lg flex items-center gap-3">
            <span>AVISO: {{ session('error') }}</span>
        </div>
    @endif

    <div class="p-6 rounded-xl border border-[#588157] bg-[#18261f] space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[#588157]/40 pb-4">
            <div>
                <h2 class="text-base font-bold uppercase text-white tracking-wider">MODULO DE POLIZAS Y GARANTIAS OFICIALES (90 DIAS)</h2>
                <p class="text-[#a3b18a] text-[11px]">Control de cobertura post-entrega, validacion de vigencia y gestion de reingresos sin costo</p>
            </div>

            <form method="GET" action="{{ route('garantias.index') }}" class="flex items-center gap-2">
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Folio GAR, Orden ST-, Serie o Cliente..." class="px-3 py-1.5 rounded bg-[#111915] border border-[#588157] text-white text-xs outline-none">
                <button type="submit" class="px-4 py-1.5 bg-[#588157] text-white font-bold rounded hover:bg-[#a3b18a] hover:text-[#18261f]">BUSCAR</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#588157]/40 text-[#a3b18a] uppercase text-[11px]">
                        <th class="py-2.5 px-3">Folio Garantia</th>
                        <th class="py-2.5 px-3">Orden / Equipo</th>
                        <th class="py-2.5 px-3">Cliente</th>
                        <th class="py-2.5 px-3">Vigencia (90 Dias)</th>
                        <th class="py-2.5 px-3">Estado</th>
                        <th class="py-2.5 px-3 text-right">Accion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#588157]/20 text-white">
                    @forelse($garantias as $g)
                        @php
                            $vigente = (date('Y-m-d') <= $g->fecha_fin);
                        @endphp
                        <tr class="hover:bg-[#1f3027]">
                            <td class="py-3 px-3 font-bold text-emerald-400">{{ $g->folio }}</td>
                            <td class="py-3 px-3">
                                <div class="font-bold">{{ $g->codigo_servicio }}</div>
                                <div class="text-[11px] text-[#a3b18a]">{{ $g->marca }} {{ $g->modelo }} ({{ $g->numero_serie }})</div>
                            </td>
                            <td class="py-3 px-3">
                                <div>{{ $g->cliente_nombre ?? 'Cliente' }}</div>
                                <div class="text-[11px] text-[#a3b18a]">{{ $g->cliente_telefono ?? '' }}</div>
                            </td>
                            <td class="py-3 px-3">
                                <div>Inicio: {{ $g->fecha_inicio }}</div>
                                <div class="font-bold {{ $vigente ? 'text-emerald-400' : 'text-rose-400' }}">Vence: {{ $g->fecha_fin }}</div>
                            </td>
                            <td class="py-3 px-3">
                                @if($g->estado === 'RECLAMADA')
                                    <span class="px-2 py-1 bg-amber-500/20 border border-amber-500 text-amber-300 rounded font-bold text-[10px] whitespace-nowrap">RECLAMADA EN TALLER</span>
                                    @if($g->motivo_reclamo)
                                        <div class="text-[10px] text-amber-200/80 mt-1 max-w-xs">{{ $g->motivo_reclamo }}</div>
                                    @endif
                                @elseif($vigente)
                                    <span class="px-2 py-1 bg-emerald-500/20 border border-emerald-500 text-emerald-300 rounded font-bold text-[10px] whitespace-nowrap">ACTIVA / VIGENTE</span>
                                @else
                                    <span class="px-2 py-1 bg-rose-500/20 border border-rose-500 text-rose-300 rounded font-bold text-[10px] whitespace-nowrap">VENCIDA</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-right">
                                @if($g->estado === 'RECLAMADA')
                                    <form method="POST" action="{{ route('garantias.resolver', $g->id) }}" class="inline-flex items-center gap-1">
                                        @csrf
                                        <input type="text" name="resolucion" placeholder="Nota resolucion..." required class="px-2 py-1 rounded bg-[#111915] border border-[#588157] text-white text-[11px]">
                                        <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded text-[11px] whitespace-nowrap">RE-ENTREGAR</button>
                                    </form>
                                @elseif($vigente)
                                    <form method="POST" action="{{ route('garantias.reclamar', $g->id) }}" class="inline-flex items-center gap-1">
                                        @csrf
                                        <input type="text" name="motivo_reclamo" placeholder="Motivo falla garantia..." required class="px-2 py-1 rounded bg-[#111915] border border-[#588157] text-white text-[11px]">
                                        <button type="submit" class="px-3 py-1 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded text-[11px] whitespace-nowrap">APLICAR GARANTIA</button>
                                    </form>
                                @else
                                    <span class="text-[#a3b18a] text-[11px]">Sin cobertura</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-[#a3b18a]">No hay polizas de garantia registradas aun.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection