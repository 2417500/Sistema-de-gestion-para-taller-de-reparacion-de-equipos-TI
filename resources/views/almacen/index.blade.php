@extends('layouts.private')

@section('title', 'Almacén & Control de Stock')
@section('page-title', 'Almacén General // Refacciones y Control de Herramientas')

@section('content')
<div class="space-y-6 font-mono text-xs">
    
    @if (session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- ALMACÉN 1: REFACCIONES Y CONSUMIBLES (STOCK SEGURIDAD > 1) -->
    <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4">
        <div class="flex justify-between items-center pb-2 border-b border-[#2d3038]">
            <div>
                <h2 class="text-sm font-bold text-[#ffb800] uppercase">1. ALMACÉN DE REFACCIONES & CONSUMIBLES</h2>
                <p class="text-[10px] text-[#8a8e9b]">REPUESTOS DESTINADOS A INSTALACIÓN PERMANENTE EN EQUIPOS</p>
            </div>
            <span class="text-[10px] bg-[#1a1c23] text-emerald-400 border border-emerald-500/30 px-2 py-1 font-bold">STOCK SEGURIDAD > 1</span>
        </div>
        
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#182238] text-white uppercase text-[10px]">
                    <th class="p-3">CÓDIGO / REFACCIÓN</th>
                    <th class="p-3">TIPO</th>
                    <th class="p-3">STOCK ACTUAL</th>
                    <th class="p-3">MÍNIMO REQUERIDO</th>
                    <th class="p-3">PRECIO UNITARIO</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#2d3038] text-white">
                @forelse($refacciones as $item)
                    <tr>
                        <td class="p-3 font-bold">{{ $item->nombre }}</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-950 text-amber-400 border border-amber-500/30 uppercase">
                                {{ $item->tipo }}
                            </span>
                        </td>
                        <td class="p-3 text-emerald-400 font-bold">{{ $item->stock_actual }} pzs</td>
                        <td class="p-3 text-sky-400 font-bold">{{ $item->stock_minimo }} pzs</td>
                        <td class="p-3">${{ number_format($item->precio_unitario, 2) }} MXN</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-[#8a8e9b]">No hay refacciones registradas en este almacén.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ALMACÉN 2: CONTROL Y DEVOLUCIÓN DE HERRAMIENTAS (TIEMPOS Y FECHAS) -->
    <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4">
        <div class="flex justify-between items-center pb-2 border-b border-[#2d3038]">
            <div>
                <h2 class="text-sm font-bold text-sky-400 uppercase">2. ALMACÉN DE HERRAMIENTAS & EQUIPO TÉCNICO</h2>
                <p class="text-[10px] text-[#8a8e9b]">HISTORIAL DE PRÉSTAMOS, HORARIOS DE SOLICITUD Y FECHAS DE ENTREGA</p>
            </div>
            <span class="text-[10px] bg-[#1a1c23] text-sky-400 border border-sky-500/30 px-2 py-1 font-bold">CONTROL DE DEVOLUCIONES</span>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#182238] text-white uppercase text-[10px]">
                    <th class="p-3">HERRAMIENTA</th>
                    <th class="p-3">TÉCNICO</th>
                    <th class="p-3">ORDEN</th>
                    <th class="p-3">CANTIDAD</th>
                    <th class="p-3">HORA PETICIÓN / APROBACIÓN</th>
                    <th class="p-3">ESTADO / REGRESO</th>
                    <th class="p-3 text-right">ACCIÓN ALMACÉN</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#2d3038] text-white">
                @forelse($prestamos as $prestamo)
                    <tr>
                        <td class="p-3 font-bold">{{ $prestamo->item_nombre }}</td>
                        <td class="p-3">{{ $prestamo->tecnico_nombre }}</td>
                        <td class="p-3 text-[#ffb800] font-bold">{{ $prestamo->codigo_servicio }}</td>
                        <td class="p-3">{{ $prestamo->cantidad }} pzs</td>
                        <td class="p-3 text-[#8a8e9b] text-[10px]">{{ $prestamo->created_at }}</td>
                        <td class="p-3">
                            @if($prestamo->estado === 'APROBADO')
                                <span class="px-2 py-0.5 rounded text-[9px] bg-amber-950 text-amber-400 border border-amber-500/30 font-bold uppercase">
                                    EN POSESIÓN TÉCNICO
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[9px] bg-emerald-950 text-emerald-400 border border-emerald-500/30 font-bold uppercase">
                                    DEVUELTO ({{ $prestamo->updated_at }})
                                </span>
                            @endif
                        </td>
                        <td class="p-3 text-right">
                            @if($prestamo->estado === 'APROBADO')
                                <form action="{{ route('almacen.devolver', $prestamo->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-sky-600 hover:bg-sky-500 text-white font-bold rounded text-[10px] uppercase transition-colors">
                                        RECIBIR DEVOLUCIÓN
                                    </button>
                                </form>
                            @else
                                <span class="text-[#8a8e9b] text-[10px] italic">STOCK CONCILIADO</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-[#8a8e9b]">No hay registros de préstamos de herramientas en el historial.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection