@extends('layouts.private')

@section('title', 'Gestión de Almacén')
@section('page-title', 'Control de Stock & Inventario')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 font-mono text-xs">

    @if (session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-rose-950/80 border border-rose-500/50 text-rose-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">warning</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- CABECERA & BOTÓN PARA REGISTRAR NUEVO ÍTEM -->
    <div class="flex items-center justify-between pb-2 border-b border-[#2d3038]">
        <div>
            <h2 class="text-sm font-bold text-white uppercase tracking-wider">PANEL DE ALMACÉN & INVENTARIO MULTI-CATÁLOGO</h2>
            <p class="text-[#8a8e9b] text-[11px]">Control de stock de herramientas de taller y catálogo de refacciones.</p>
        </div>
        
        <button onclick="document.getElementById('modal-nuevo-item').classList.remove('hidden')" 
            class="px-4 py-2 bg-[#ffb800] text-[#0d0f12] font-extrabold uppercase rounded hover:bg-white transition-colors flex items-center gap-2 shadow-lg">
            <span class="material-symbols-outlined text-base">add_box</span>
            <span>REGISTRAR NUEVO ÍTEM</span>
        </button>
    </div>

    <!-- 1. ALMACÉN DE REFACCIONES -->
    <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4 rounded">
        <div class="flex items-center justify-between pb-3 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb800]">memory</span>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">ALMACÉN DE REFACCIONES</h3>
            </div>
            <span class="text-[10px] text-[#8a8e9b] font-bold">TOTAL: {{ count($refacciones) }} ÍTEMS</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-white border-collapse">
                <thead>
                    <tr class="border-b border-[#2d3038] text-[#8a8e9b] uppercase text-[10px]">
                        <th class="p-2">ÍTEM / REFACCIÓN</th>
                        <th class="p-2 text-center">VALOR / PRECIO UNITARIO</th>
                        <th class="p-2 text-center">STOCK ACTUAL</th>
                        <th class="p-2 text-right">REABASTECER STOCK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3038]/50">
                    @forelse ($refacciones as $item)
                        <tr class="hover:bg-[#181a20] transition-colors">
                            <td class="p-2 font-bold">{{ $item->nombre }}</td>
                            <td class="p-2 text-center text-amber-400 font-bold">${{ number_format($item->precio_unitario, 2) }}</td>
                            <td class="p-2 text-center">
                                @if($item->stock_actual <= 2)
                                    <span class="px-2 py-0.5 bg-rose-950/80 border border-rose-500/50 text-rose-300 font-extrabold rounded uppercase text-[10px]">
                                        {{ $item->stock_actual }} (CRÍTICO)
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-emerald-950/80 border border-emerald-500/50 text-emerald-400 font-bold rounded text-[10px]">
                                        {{ $item->stock_actual }} pzs
                                    </span>
                                @endif
                            </td>
                            <td class="p-2 text-right">
                                <form action="{{ route('almacen.reabastecer', $item->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="cantidad" min="1" value="5" required 
                                        class="w-16 bg-[#181a20] border border-[#2d3038] text-white p-1 text-center rounded focus:border-[#ffb800] focus:outline-none">
                                    <button type="submit" class="px-3 py-1 bg-[#252830] hover:bg-[#ffb800] hover:text-[#0d0f12] text-white font-bold rounded uppercase text-[10px] transition-colors flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">add</span>
                                        <span>SUMAR</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-[#8a8e9b]">No hay refacciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. ALMACÉN DE HERRAMIENTAS -->
    <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4 rounded">
        <div class="flex items-center justify-between pb-3 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb800]">construction</span>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">ALMACÉN DE HERRAMIENTAS DE TALLER</h3>
            </div>
            <span class="text-[10px] text-[#8a8e9b] font-bold">TOTAL: {{ count($herramientas) }} ÍTEMS</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-white border-collapse">
                <thead>
                    <tr class="border-b border-[#2d3038] text-[#8a8e9b] uppercase text-[10px]">
                        <th class="p-2">HERRAMIENTA</th>
                        <th class="p-2 text-center">DISPONIBLE EN STOCK</th>
                        <th class="p-2 text-right">REABASTECER STOCK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3038]/50">
                    @forelse ($herramientas as $item)
                        <tr class="hover:bg-[#181a20] transition-colors">
                            <td class="p-2 font-bold">{{ $item->nombre }}</td>
                            <td class="p-2 text-center">
                                @if($item->stock_actual == 0)
                                    <span class="px-2 py-0.5 bg-rose-950/80 border border-rose-500/50 text-rose-300 font-extrabold rounded uppercase text-[10px]">
                                        AGOTADO (0)
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-emerald-950/80 border border-emerald-500/50 text-emerald-400 font-bold rounded text-[10px]">
                                        {{ $item->stock_actual }} disponibles
                                    </span>
                                @endif
                            </td>
                            <td class="p-2 text-right">
                                <form action="{{ route('almacen.reabastecer', $item->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="cantidad" min="1" value="1" required 
                                        class="w-16 bg-[#181a20] border border-[#2d3038] text-white p-1 text-center rounded focus:border-[#ffb800] focus:outline-none">
                                    <button type="submit" class="px-3 py-1 bg-[#252830] hover:bg-[#ffb800] hover:text-[#0d0f12] text-white font-bold rounded uppercase text-[10px] transition-colors flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">add</span>
                                        <span>SUMAR</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-[#8a8e9b]">No hay herramientas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. DESPACHO DE HERRAMIENTAS Y SOLICITUDES PENDIENTES -->
    @if(count($herramientasParaEnviar) > 0 || count($solicitudesAutorizadas) > 0)
        <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4 rounded">
            <div class="flex items-center gap-2 pb-3 border-b border-[#2d3038]">
                <span class="material-symbols-outlined text-[#ffb800]">local_shipping</span>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">ENTREGAS Y DESPACHOS PENDIENTES</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- HERRAMIENTAS PARA ENVIAR A TÉCNICOS -->
                <div>
                    <h4 class="text-white font-bold uppercase text-[11px] mb-2 text-[#ffb800]">Herramientas Autorizadas por Admin:</h4>
                    <div class="space-y-2">
                        @forelse($herramientasParaEnviar as $h)
                            <div class="bg-[#181a20] border border-[#2d3038] p-3 rounded flex items-center justify-between text-white">
                                <div>
                                    <p class="font-bold">{{ $h->herramienta_nombre }} ({{ $h->cantidad }} pzs)</p>
                                    <p class="text-[10px] text-[#8a8e9b]">Técnico: {{ $h->tecnico_nombre }}</p>
                                </div>
                                <form action="{{ route('almacen.herramientas.enviar', $h->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-[#ffb800] text-[#0d0f12] font-extrabold rounded hover:bg-white uppercase text-[10px]">
                                        DESPACHAR
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-[#8a8e9b] text-[11px]">No hay herramientas pendientes de salida.</p>
                        @endforelse
                    </div>
                </div>

                <!-- REFACCIONES AUTORIZADAS PARA ORDENES -->
                <div>
                    <h4 class="text-white font-bold uppercase text-[11px] mb-2 text-[#ffb800]">Refacciones Solicitadas por Técnico:</h4>
                    <div class="space-y-2">
                        @forelse($solicitudesAutorizadas as $s)
                            <div class="bg-[#181a20] border border-[#2d3038] p-3 rounded flex items-center justify-between text-white">
                                <div>
                                    <p class="font-bold">{{ $s->item_nombre }} ({{ $s->cantidad }} pzs)</p>
                                    <p class="text-[10px] text-[#8a8e9b]">Orden: {{ $s->codigo_servicio }} | Técnico: {{ $s->tecnico_nombre }}</p>
                                </div>
                                <form action="{{ route('almacen.despachar', $s->orden_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-500 text-[#0d0f12] font-extrabold rounded hover:bg-white uppercase text-[10px]">
                                        ENTREGAR REFACCIÓN
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-[#8a8e9b] text-[11px]">No hay solicitudes de refacciones pendientes.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

<!-- MODAL REGISTRO DE NUEVO ÍTEM EN ALMACÉN -->
<div id="modal-nuevo-item" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 max-w-md w-full space-y-4 font-mono text-xs rounded text-white shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb800]">add_box</span>
                <h3 class="text-sm font-bold uppercase tracking-wider">REGISTRAR EN CATÁLOGOS DE ALMACÉN</h3>
            </div>
            <button onclick="document.getElementById('modal-nuevo-item').classList.add('hidden')" class="text-[#8a8e9b] hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="{{ route('almacen.nuevoItem') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Tipo de Almacén / Categoría *</label>
                <select name="tipo" id="modal_tipo_item" required onchange="togglePrecioField(this.value)"
                    class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
                    <option value="refaccion">ALMACÉN DE REFACCIONES</option>
                    <option value="herramienta">ALMACÉN DE HERRAMIENTAS</option>
                </select>
            </div>

            <div>
                <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Nombre del Producto / Herramienta *</label>
                <input type="text" name="nombre" required placeholder="Ej: Pantalla LED 15.6, Multímetro Digital..." 
                    class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Stock Inicial *</label>
                    <input type="number" name="stock_actual" min="1" value="10" required 
                        class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
                </div>

                <div id="wrapper_precio_unitario">
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Costo / Valor ($) *</label>
                    <input type="number" step="0.01" name="precio_unitario" id="modal_precio_unitario" value="0.00" placeholder="0.00"
                        class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-[#2d3038]">
                <button type="button" onclick="document.getElementById('modal-nuevo-item').classList.add('hidden')" 
                    class="px-4 py-2 bg-[#181a20] border border-[#2d3038] text-white rounded hover:bg-[#252830]">
                    CANCELAR
                </button>
                <button type="submit" class="px-5 py-2 bg-[#ffb800] text-[#0d0f12] font-extrabold uppercase rounded hover:bg-white transition-colors">
                    GUARDAR EN ALMACÉN
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePrecioField(tipo) {
    const wrapper = document.getElementById('wrapper_precio_unitario');
    const inputPrecio = document.getElementById('modal_precio_unitario');
    
    if (tipo === 'herramienta') {
        wrapper.classList.add('hidden');
        inputPrecio.value = "0.00";
    } else {
        wrapper.classList.remove('hidden');
    }
}
</script>
@endsection