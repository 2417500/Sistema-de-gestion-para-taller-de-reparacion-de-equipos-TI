@extends('layouts.private')

@section('title', 'Dashboard')
@section('page-title', 'Workbench de Control')

@section('content')
<div class="space-y-6 font-mono text-xs">

    @if (session('error'))
        <div class="bg-rose-950/80 border border-rose-500/50 text-rose-300 p-4 rounded flex items-center gap-2">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- TARJETAS DE INDICADORES SIN PALABRAS DE ICONOS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="admin-card p-4 rounded shadow space-y-2 border border-[#588157]" style="background-color: var(--bg-card, #3a5a40);">
            <div class="flex justify-between items-center dynamic-muted font-subtitulos">
                <span class="uppercase font-bold text-xs">COLA EN RECEPCIÓN</span>
            </div>
            <div class="text-2xl font-bold dynamic-title font-titulos">
                {{ $ordenes->filter(fn($o) => strtolower($o->estado ?? '') === 'pendiente')->count() }}
            </div>
            <p class="text-[10px] opacity-80 dynamic-title">Equipos pendientes de diagnóstico inicial</p>
        </div>

        <div class="admin-card p-4 rounded shadow space-y-2 border border-[#588157]" style="background-color: var(--bg-card, #3a5a40);">
            <div class="flex justify-between items-center text-[#a3b18a] font-subtitulos">
                <span class="uppercase font-bold text-xs">EN BANCO TÉCNICO</span>
            </div>
            <div class="text-2xl font-bold text-[#a3b18a] font-titulos">
                {{ $ordenes->filter(fn($o) => strtolower($o->estado ?? '') === 'en_proceso')->count() }}
            </div>
            <p class="text-[10px] opacity-80 dynamic-title">Pruebas eléctricas y reemplazo activo</p>
        </div>

        <div class="admin-card p-4 rounded shadow space-y-2 border border-[#588157]" style="background-color: var(--bg-card, #3a5a40);">
            <div class="flex justify-between items-center text-emerald-400 font-subtitulos">
                <span class="uppercase font-bold text-xs">LISTO PARA ENTREGA</span>
            </div>
            <div class="text-2xl font-bold text-emerald-400 font-titulos">
                {{ $ordenes->filter(fn($o) => strtolower($o->estado ?? '') === 'terminado')->count() }}
            </div>
            <p class="text-[10px] opacity-80 dynamic-title">Certificación de control de calidad QA</p>
        </div>

        <div class="admin-card p-4 rounded shadow space-y-2 border border-[#588157]" style="background-color: var(--bg-card, #3a5a40);">
            <div class="flex justify-between items-center text-amber-400 font-subtitulos">
                <span class="uppercase font-bold text-xs">APROBACIÓN ADMIN</span>
            </div>
            <div class="text-2xl font-bold text-amber-400 font-titulos">
                {{ $ordenes->where('requiere_aprobacion_admin', 1)->count() }}
            </div>
            <p class="text-[10px] opacity-80 dynamic-title">Exceso refacciones o herramientas</p>
        </div>
    </div>

    <!-- TABLA PRINCIPAL DE ÓRDENES -->
    <div class="admin-card border border-[#588157] p-6 space-y-4 rounded shadow" style="background-color: var(--bg-card, #3a5a40);">
        <div class="flex justify-between items-center pb-4 border-b border-[#588157]">
            <div class="flex items-center gap-2">
                <h2 class="text-sm font-bold dynamic-title uppercase tracking-wider font-titulos">COLA ACTIVA DE ÓRDENES DE SERVICIO</h2>
            </div>
            <span class="text-[10px] dynamic-muted uppercase font-subtitulos">MATRIZ TÉCNICA // PRIORIDAD DE BANCO</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse font-mono">
                <thead>
                    <tr class="uppercase text-[10px] border-b border-[#588157] font-subtitulos" style="background-color: var(--table-header, #24382c);">
                        <th class="p-3 font-semibold tracking-wider dynamic-title">CÓDIGO SERVICIO</th>
                        <th class="p-3 font-semibold tracking-wider dynamic-title">EQUIPO / MODELO</th>
                        <th class="p-3 font-semibold tracking-wider dynamic-title">N° SERIE</th>
                        <th class="p-3 font-semibold tracking-wider dynamic-title">CLIENTE</th>
                        <th class="p-3 font-semibold tracking-wider dynamic-title">TÉCNICO</th>
                        <th class="p-3 font-semibold tracking-wider dynamic-title">ESTADO</th>
                        <th class="p-3 font-semibold tracking-wider dynamic-title">ESTADO ORDEN</th>
                        <th class="p-3 font-semibold tracking-wider text-right dynamic-title">ACCIÓN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#588157]/40 dynamic-title">
                    @forelse ($ordenes as $orden)
                        <tr class="hover:bg-[#588157]/20 transition-colors">
                            <td class="p-3 text-[#a3b18a] font-bold">{{ $orden->codigo_servicio }}</td>
                            <td class="p-3 uppercase font-bold">{{ $orden->marca }} {{ $orden->modelo }}</td>
                            <td class="p-3 opacity-80 uppercase">{{ $orden->numero_serie ?? 'SIN REGISTRO' }}</td>
                            <td class="p-3 font-bold">{{ $orden->cliente_nombre ?? 'Cliente Desconocido' }}</td>
                            <td class="p-3 opacity-90">{{ $orden->tecnico_nombre ?? 'Por asignar' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 text-[10px] font-bold border border-[#588157] rounded uppercase bg-[#24382c] text-[#a3b18a]">
                                    {{ str_replace('_', ' ', $orden->estado) }}
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

                            <!-- ACCIONES COMPLETAS -->
                            <td class="p-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('ordenes.show', $orden->id) }}" class="px-3 py-1 bg-[#588157] border border-[#588157] text-white font-bold uppercase rounded hover:bg-[#a3b18a] hover:text-[#344e41] transition-colors inline-block text-[10px]">
                                        VER ORDEN
                                    </a>

                                    @if(empty($orden->tecnico_id))
                                        <form action="{{ route('ordenes.atender', $orden->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-[#a3b18a] text-[#344e41] font-bold uppercase rounded hover:bg-[#588157] hover:text-white transition-colors text-[10px] shadow">
                                                ATENDER ORDEN
                                            </button>
                                        </form>
                                    @elseif($orden->tecnico_id == Auth::id())
                                        <span class="px-2 py-1 bg-sky-950/80 border border-sky-400 text-sky-300 font-bold uppercase rounded text-[10px]">
                                            EN ATENCIÓN
                                        </span>
                                    @else
                                        <button disabled class="px-3 py-1 bg-[#24382c] border border-[#588157]/50 text-[#a3b18a] font-bold uppercase rounded text-[10px] cursor-not-allowed opacity-75">
                                            ATENDIDO POR: {{ $orden->tecnico_nombre }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center opacity-80 uppercase">No hay órdenes registradas en el sistema.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection