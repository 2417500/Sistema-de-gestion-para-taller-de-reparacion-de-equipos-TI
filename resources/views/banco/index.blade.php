@extends('layouts.private')

@section('title', 'Banco Técnico')
@section('page-title', 'Banco Técnico // Panel de Diagnóstico & Trabajo')

@section('content')
<div class="space-y-6 font-mono text-xs">
    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038]">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-[#2d3038]">
            <h2 class="text-sm font-bold text-[#ffb800] uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-base">build</span>
                PANEL DE DIAGNÓSTICO & TRABAJO TÉCNICO
            </h2>
            <span class="text-[10px] text-[#8a8e9b] uppercase">GESTIÓN DE EQUIPOS ASIGNADOS EN BANCO TÉCNICO</span>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/40 text-emerald-400 p-3 rounded mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/40 text-red-400 p-3 rounded mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">warning</span>
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
                            <td class="p-3 font-bold text-[#ffb800]">{{ $orden->codigo_servicio }}</td>
                            <td class="p-3 text-white font-bold">{{ $orden->marca }} {{ $orden->modelo }}</td>
                            <td class="p-3 text-[#8a8e9b]">{{ $orden->numero_serie }}</td>
                            <td class="p-3 text-[#c4c6cf]">{{ Str::limit($orden->falla_reportada, 40) }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 text-[10px] bg-[#ffb800]/10 border border-[#ffb800]/40 text-[#ffb800] uppercase font-bold rounded">
                                    {{ str_replace('_', ' ', $orden->estado) }}
                                </span>
                            </td>

                            <!-- COLUMNA DE CARGA DE EVIDENCIAS FOTOGRÁFICAS -->
                            <td class="p-3">
                                @if(in_array($orden->estado, ['En_Reparacion', 'EN_PROCESO', 'En_Proceso']))
                                    <form action="{{ route('banco.subirFotosEntrega', $orden->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                                        @csrf
                                        <label class="cursor-pointer bg-[#1a1c23] border border-[#ffb800]/40 text-[#ffb800] hover:bg-[#ffb800] hover:text-black text-[10px] px-2.5 py-1.5 rounded font-bold uppercase transition-colors flex items-center gap-1">
                                            <span class="material-symbols-outlined text-xs">photo_camera</span>
                                            <span>{{ !empty($orden->fotos_entrega) ? 'CAMBIAR' : 'SUBIR FOTOS' }}</span>
                                            <input type="file" name="fotos_entrega[]" multiple accept="image/*" class="hidden" onchange="this.form.submit()">
                                        </label>

                                        @if(!empty($orden->fotos_entrega))
                                            <span class="text-emerald-400 text-[10px] flex items-center font-bold">
                                                <span class="material-symbols-outlined text-xs mr-0.5">check_circle</span> LISTO
                                            </span>
                                        @endif
                                    </form>
                                @else
                                    <span class="text-[#8a8e9b] text-[10px]">—</span>
                                @endif
                            </td>

                            <!-- ACCIONES TÉCNICAS -->
                            <td class="p-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($orden->estado === 'Pendiente')
                                        <a href="{{ route('banco.diagnosticar', $orden->id) }}" 
                                           class="px-3 py-1.5 bg-[#ffb800] text-[#0d0f12] font-bold uppercase rounded hover:bg-white transition-colors text-[10px]">
                                            DIAGNOSTICAR
                                        </a>
                                    @elseif(in_array($orden->estado, ['En_Reparacion', 'EN_PROCESO', 'En_Proceso']))
                                        <form action="{{ route('banco.finalizar', $orden->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold uppercase rounded transition-colors text-[10px] flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">check</span>
                                                <span>FINALIZAR REPARACIÓN</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-[#8a8e9b]">
                                No hay órdenes de servicio activas en el Banco Técnico.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection