@extends('layouts.private')

@section('title', 'Almacén & Stock')
@section('page-title', 'Almacén // Control de Inventario y Herramientas')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 font-mono text-xs">

    @if (session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-rose-950/80 border border-rose-500/50 text-rose-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- SECCIÓN 1: HERRAMIENTAS AUTORIZADAS POR ADMIN LISTAS PARA DESPACHO -->
    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038] space-y-4">
        <div class="flex justify-between items-center pb-4 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb800]">handyman</span>
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">HERRAMIENTAS AUTORIZADAS POR ADMIN (PENDIENTES DE ENVÍO)</h2>
            </div>
            <span class="text-[10px] text-[#ffb800] bg-[#1d212b] px-2 py-0.5 border border-[#ffb800]/30 font-bold uppercase">Despacho Físico</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#182238] text-white uppercase text-[10px] border-b border-[#2d3038]">
                        <th class="p-3">HERRAMIENTA</th>
                        <th class="p-3">TÉCNICO SOLICITANTE</th>
                        <th class="p-3">CANTIDAD</th>
                        <th class="p-3">ESTADO</th>
                        <th class="p-3 text-right">ACCIÓN ALMACÉN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3038] text-white">
                    @forelse ($herramientasParaEnviar as $h)
                        <tr class="hover:bg-[#16181f]/50 transition-colors">
                            <td class="p-3 text-[#ffb800] font-bold">{{ $h->herramienta_nombre }}</td>
                            <td class="p-3 font-bold uppercase">{{ $h->tecnico_nombre }}</td>
                            <td class="p-3 font-bold">{{ $h->cantidad }} pza(s)</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 bg-blue-950/60 border border-blue-500/50 text-blue-400 text-[10px] uppercase font-bold rounded">
                                    AUTORIZADO / LISTO PARA ENTREGAR
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <form action="{{ route('almacen.herramientas.enviar', $h->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-[#ffb800] text-[#0d0f12] font-bold uppercase rounded hover:bg-white transition-colors text-[10px]">
                                        ENVIAR HERRAMIENTA
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-[#8a8e9b] uppercase">No hay herramientas autorizadas pendientes por despachar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECCIÓN 2: CONTROL DE PRÉSTAMOS ACTIVOS EN POSESIÓN DEL TÉCNICO -->
    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038] space-y-4">
        <div class="flex justify-between items-center pb-4 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb800]">assignment_return</span>
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">HERRAMIENTAS EN PRÉSTAMO / POSESIÓN TÉCNICO</h2>
            </div>
            <span class="text-[10px] text-[#8a8e9b] uppercase">Seguimiento de Instrumental</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#182238] text-white uppercase text-[10px] border-b border-[#2d3038]">
                        <th class="p-3">HERRAMIENTA</th>
                        <th class="p-3">TÉCNICO</th>
                        <th class="p-3">CANTIDAD</th>
                        <th class="p-3">ESTADO ACTUAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3038] text-white">
                    @forelse ($prestamosActivos as $p)
                        <tr class="hover:bg-[#16181f]/50 transition-colors">
                            <td class="p-3 text-white font-bold">{{ $p->herramienta_nombre }}</td>
                            <td class="p-3 uppercase text-[#8a8e9b]">{{ $p->tecnico_nombre }}</td>
                            <td class="p-3 text-[#ffb800] font-bold">{{ $p->cantidad }} pza(s)</td>
                            <td class="p-3">
                                @if($p->estado === 'ENVIADO_TECNICO')
                                    <span class="px-2 py-0.5 bg-indigo-950/60 border border-indigo-500/50 text-indigo-400 text-[10px] uppercase font-bold rounded">
                                        EN TRÁNSITO / PENDIENTE RECIBIR TÉCNICO
                                    </span>
                                @elseif($p->estado === 'EN_USO')
                                    <span class="px-2 py-0.5 bg-emerald-950/60 border border-emerald-500/50 text-emerald-400 text-[10px] uppercase font-bold rounded">
                                        EN USO POR EL TÉCNICO
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-[#8a8e9b] uppercase">No hay préstamos activos de herramientas en taller.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECCIÓN 3: CATÁLOGO DE REFACCIONES E INVENTARIO GENERAL -->
    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038] space-y-4">
        <div class="flex justify-between items-center pb-4 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb800]">inventory_2</span>
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">INVENTARIO DE REFACCIONES & STOCK FISICO</h2>
            </div>
            <span class="text-[10px] text-[#8a8e9b] uppercase">Catálogo General</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#182238] text-white uppercase text-[10px] border-b border-[#2d3038]">
                        <th class="p-3">TIPO</th>
                        <th class="p-3">NOMBRE</th>
                        <th class="p-3">STOCK ACTUAL</th>
                        <th class="p-3 text-right">PRECIO UNITARIO</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3038] text-white">
                    @forelse ($refacciones as $item)
                        <tr class="hover:bg-[#16181f]/50 transition-colors">
                            <td class="p-3 text-[#ffb800] font-bold uppercase">{{ $item->tipo }}</td>
                            <td class="p-3 font-bold">{{ $item->nombre }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $item->stock_actual > 0 ? 'bg-emerald-950 text-emerald-400 border border-emerald-500/30' : 'bg-rose-950 text-rose-400 border border-rose-500/30' }}">
                                    {{ $item->stock_actual }} pzs
                                </span>
                            </td>
                            <td class="p-3 text-right font-bold text-[#ffb800]">${{ number_format($item->precio_unitario, 2) }} MXN</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-[#8a8e9b] uppercase">No hay refacciones registradas en catálogo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection