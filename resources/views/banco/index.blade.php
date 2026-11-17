@extends('layouts.private')

@section('title', 'Banco Técnico')
@section('page-title', 'Banco Técnico // Panel de Diagnóstico & Trabajo')

@section('content')
<div class="space-y-6 font-mono text-xs">
    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038] rounded">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-[#2d3038]">
            <h2 class="text-sm font-bold text-[#a3b18a] uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.4-2.4c.4-.4.4-1 0-1.3z"/></svg>
                <span>PANEL DE DIAGNÓSTICO & TRABAJO TÉCNICO</span>
            </h2>
            <span class="text-[10px] text-[#8a8e9b] uppercase">GESTIÓN DE EQUIPOS ASIGNADOS EN BANCO TÉCNICO</span>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/40 text-emerald-400 p-3 rounded mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/40 text-red-400 p-3 rounded mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left font-mono text-xs">
                <thead>
                    <tr class="border-b border-[#2d3038] text-[#8a8e9b] bg-[#16181f]">
                        <th class="p-3">CÓDIGO</th>
                        <th class="p-3">EQUIPO</th>
                        <th class="p-3">N° SERIE</th>
                        <th class="p-3">FALLA REPORTADA</th>
                        <th class="p-3">ESTADO</th>
                        <th class="p-3">EVIDENCIAS (SALIDA)</th>
                        <th class="p-3 text-right">ACCIÓN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3038]">
                    @forelse($ordenes as $orden)
                        <tr class="hover:bg-[#16181f]/50 transition-colors">
                            <td class="p-3 font-bold text-[#a3b18a]">{{ $orden->codigo_servicio }}</td>
                            <td class="p-3 text-white font-bold">{{ $orden->marca }} {{ $orden->modelo }}</td>
                            <td class="p-3 text-[#8a8e9b]">{{ $orden->numero_serie }}</td>
                            <td class="p-3 text-[#c4c6cf]">{{ Str::limit($orden->falla_reportada, 40) }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 text-[10px] bg-[#588157]/20 border border-[#588157] text-[#a3b18a] uppercase font-bold rounded">
                                    {{ str_replace('_', ' ', $orden->estado) }}
                                </span>
                            </td>

                            <!-- COLUMNA DE CARGA DE EVIDENCIAS FOTOGRÁFICAS (SOLO DESPUÉS DE VALIDACIÓN DEL CLIENTE) -->
                            <td class="p-3">
                                @if(in_array($orden->estado, ['En_Reparacion', 'EN_PROCESO', 'En_Proceso']))
                                    <form action="{{ route('banco.subirFotosEntrega', $orden->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                                        @csrf
                                        <label class="cursor-pointer bg-[#1a1c23] border border-[#588157] text-[#a3b18a] hover:bg-[#588157] hover:text-white text-[10px] px-2.5 py-1.5 rounded font-bold uppercase transition-colors inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg>
                                            <span>{{ !empty($orden->fotos_entrega) ? 'CAMBIAR' : 'SUBIR FOTOS' }}</span>
                                            <input type="file" name="fotos_entrega[]" multiple accept="image/*" class="hidden" onchange="this.form.submit()">
                                        </label>

                                        @if(!empty($orden->fotos_entrega))
                                            <span class="text-emerald-400 text-[10px] inline-flex items-center gap-1 font-bold">
                                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                                <span>LISTO</span>
                                            </span>
                                        @endif
                                    </form>
                                @else
                                    <span class="text-[#8a8e9b] text-[10px]">—</span>
                                @endif
                            </td>

                            <!-- ACCIONES TÉCNICAS SEGÚN ETAPA DEL FLUJO -->
                            <td class="p-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if(in_array($orden->estado, ['Pendiente', 'Diagnosticando']))
                                        <a href="{{ route('banco.diagnosticar', $orden->id) }}" 
                                           class="px-3 py-1.5 bg-[#588157] text-white font-bold uppercase rounded hover:bg-[#a3b18a] hover:text-[#1d2e26] transition-colors text-[10px] inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                                            <span>DIAGNOSTICAR</span>
                                        </a>
                                    @elseif($orden->estado === 'Cotizado')
                                        <span class="px-2.5 py-1 bg-sky-950/80 border border-sky-500/50 text-sky-300 font-bold uppercase rounded text-[10px]">
                                            ESPERANDO APROBACIÓN CLIENTE
                                        </span>
                                    @elseif(in_array($orden->estado, ['En_Reparacion', 'EN_PROCESO', 'En_Proceso']))
                                        <form action="{{ route('banco.finalizar', $orden->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold uppercase rounded transition-colors text-[10px] inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                                <span>FINALIZAR REPARACIÓN</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[#8a8e9b] text-[10px] uppercase font-bold">{{ str_replace('_', ' ', $orden->estado) }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-[#8a8e9b]">
                                No tienes órdenes asignadas en tu Banco Técnico. Toma una orden desde el Dashboard.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection