@extends('layouts.private')

@section('title', 'Gestión de Almacén')
@section('page-title', 'Control de Stock & Inventario')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 font-mono text-xs">

    @if (session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-rose-950/80 border border-rose-500/50 text-rose-300 p-4 rounded flex items-center gap-2">
            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- CABECERA & BOTÓN PARA REGISTRAR NUEVO ÍTEM -->
    <div class="flex flex-wrap items-center justify-between gap-4 pb-2 border-b border-[#2d3038]">
        <div>
            <h2 class="text-sm font-bold text-white uppercase tracking-wider">PANEL DE ALMACÉN & INVENTARIO MULTI-CATÁLOGO</h2>
            <p class="text-[#8a8e9b] text-[11px]">Control de stock de herramientas de taller y catálogo de refacciones.</p>
        </div>
        
        <button type="button" onclick="document.getElementById('modal-nuevo-item').classList.remove('hidden')" 
            class="px-4 py-2 bg-[#588157] text-white font-extrabold uppercase rounded hover:bg-[#a3b18a] hover:text-[#1d2e26] transition-colors flex items-center gap-2 shadow-lg">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
            <span>REGISTRAR NUEVO ÍTEM</span>
        </button>
    </div>

    <!-- 1. ALMACÉN DE REFACCIONES -->
    <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4 rounded">
        <div class="flex items-center justify-between pb-3 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 fill-current text-[#a3b18a]" viewBox="0 0 24 24"><path d="M15 9H9v6h6V9zm-2 4h-2v-2h2v2zm8-2V9h-2V7c0-1.1-.9-2-2-2h-2V3h-2v2h-2V3H9v2H7c-1.1 0-2 .9-2 2v2H3v2h2v2H3v2h2v2c0 1.1.9 2 2 2h2v2h2v-2h2v2h2v-2h2c1.1 0 2-.9 2-2v-2h2v-2h-2v-2h2zm-4 6H7V7h10v10z"/></svg>
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
                            <td class="p-2 text-center text-[#a3b18a] font-bold">${{ number_format($item->precio_unitario, 2) }}</td>
                            <td class="p-2 text-center">
                                <div class="inline-flex items-center justify-center gap-2">
                                    @if($item->stock_actual <= 2)
                                        <span class="px-2 py-0.5 bg-rose-950/80 border border-rose-500/50 text-rose-300 font-extrabold rounded uppercase text-[10px]">
                                            {{ $item->stock_actual }} (CRÍTICO)
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 bg-emerald-950/80 border border-emerald-500/50 text-emerald-400 font-bold rounded text-[10px]">
                                            {{ $item->stock_actual }} pzs
                                        </span>
                                    @endif

                                    <button type="button"
                                        data-id="{{ $item->id }}"
                                        data-nombre="{{ $item->nombre }}"
                                        data-precio="{{ $item->precio_unitario }}"
                                        data-tipo="refaccion"
                                        onclick="abrirModalEditar(this)"
                                        class="px-2 py-0.5 bg-[#588157]/30 hover:bg-[#588157] border border-[#588157] text-white font-bold rounded uppercase text-[10px] transition-colors inline-flex items-center gap-1"
                                        title="Editar nombre y valor de refacción">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                        <span>EDITAR</span>
                                    </button>
                                </div>
                            </td>
                            <td class="p-2 text-right">
                                <form action="{{ route('almacen.reabastecer', $item->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="cantidad" min="1" value="5" required 
                                        class="w-16 bg-[#181a20] border border-[#2d3038] text-white p-1 text-center rounded focus:border-[#588157] focus:outline-none">
                                    <button type="submit" class="px-3 py-1 bg-[#252830] hover:bg-[#588157] text-white font-bold rounded uppercase text-[10px] transition-colors inline-flex items-center gap-1">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
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
                <svg class="w-5 h-5 fill-current text-[#a3b18a]" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.4-2.4c.4-.4.4-1 0-1.3z"/></svg>
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
                                <div class="inline-flex items-center justify-center gap-2">
                                    @if($item->stock_actual == 0)
                                        <span class="px-2 py-0.5 bg-rose-950/80 border border-rose-500/50 text-rose-300 font-extrabold rounded uppercase text-[10px]">
                                            AGOTADO (0)
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 bg-emerald-950/80 border border-emerald-500/50 text-emerald-400 font-bold rounded text-[10px]">
                                            {{ $item->stock_actual }} disponibles
                                        </span>
                                    @endif

                                    <button type="button"
                                        data-id="{{ $item->id }}"
                                        data-nombre="{{ $item->nombre }}"
                                        data-precio="0"
                                        data-tipo="herramienta"
                                        onclick="abrirModalEditar(this)"
                                        class="px-2 py-0.5 bg-[#588157]/30 hover:bg-[#588157] border border-[#588157] text-white font-bold rounded uppercase text-[10px] transition-colors inline-flex items-center gap-1"
                                        title="Editar nombre de herramienta">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                        <span>EDITAR</span>
                                    </button>
                                </div>
                            </td>
                            <td class="p-2 text-right">
                                <form action="{{ route('almacen.reabastecer', $item->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="cantidad" min="1" value="1" required 
                                        class="w-16 bg-[#181a20] border border-[#2d3038] text-white p-1 text-center rounded focus:border-[#588157] focus:outline-none">
                                    <button type="submit" class="px-3 py-1 bg-[#252830] hover:bg-[#588157] text-white font-bold rounded uppercase text-[10px] transition-colors inline-flex items-center gap-1">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
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
                <svg class="w-5 h-5 fill-current text-[#a3b18a]" viewBox="0 0 24 24"><path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">ENTREGAS Y DESPACHOS PENDIENTES</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- HERRAMIENTAS PARA ENVIAR A TÉCNICOS -->
                <div>
                    <h4 class="text-[#a3b18a] font-bold uppercase text-[11px] mb-2">Herramientas Autorizadas por Admin:</h4>
                    <div class="space-y-2">
                        @forelse($herramientasParaEnviar as $h)
                            <div class="bg-[#181a20] border border-[#2d3038] p-3 rounded flex items-center justify-between text-white">
                                <div>
                                    <p class="font-bold">{{ $h->herramienta_nombre }} ({{ $h->cantidad }} pzs)</p>
                                    <p class="text-[10px] text-[#8a8e9b]">Técnico: {{ $h->tecnico_nombre }}</p>
                                </div>
                                <form action="{{ route('almacen.herramientas.enviar', $h->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-[#588157] text-white font-extrabold rounded hover:bg-[#a3b18a] hover:text-[#1d2e26] uppercase text-[10px] transition-colors">
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
                    <h4 class="text-[#a3b18a] font-bold uppercase text-[11px] mb-2">Refacciones Solicitadas por Técnico:</h4>
                    <div class="space-y-2">
                        @forelse($solicitudesAutorizadas as $s)
                            <div class="bg-[#181a20] border border-[#2d3038] p-3 rounded flex items-center justify-between text-white">
                                <div>
                                    <p class="font-bold">{{ $s->item_nombre }} ({{ $s->cantidad }} pzs)</p>
                                    <p class="text-[10px] text-[#8a8e9b]">Orden: {{ $s->codigo_servicio }} | Técnico: {{ $s->tecnico_nombre }}</p>
                                </div>
                                <form action="{{ route('almacen.despachar', $s->orden_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white font-extrabold rounded hover:bg-emerald-500 uppercase text-[10px] transition-colors">
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
                <svg class="w-5 h-5 fill-current text-[#a3b18a]" viewBox="0 0 24 24"><path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                <h3 class="text-sm font-bold uppercase tracking-wider">REGISTRAR EN CATÁLOGOS DE ALMACÉN</h3>
            </div>
            <button type="button" onclick="document.getElementById('modal-nuevo-item').classList.add('hidden')" class="text-[#8a8e9b] hover:text-white">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>

        <form action="{{ route('almacen.nuevoItem') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Tipo de Almacén / Categoría *</label>
                <select name="tipo" id="modal_tipo_item" required onchange="togglePrecioField(this.value)"
                    class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#588157] focus:outline-none">
                    <option value="refaccion">ALMACÉN DE REFACCIONES</option>
                    <option value="herramienta">ALMACÉN DE HERRAMIENTAS</option>
                </select>
            </div>

            <div>
                <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Nombre del Producto / Herramienta *</label>
                <input type="text" name="nombre" required placeholder="Ej: Pantalla LED 15.6, Multímetro Digital..." 
                    class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#588157] focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Stock Inicial *</label>
                    <input type="number" name="stock_actual" min="1" value="10" required 
                        class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#588157] focus:outline-none">
                </div>

                <div id="wrapper_precio_unitario">
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Costo / Valor ($) *</label>
                    <input type="number" step="0.01" name="precio_unitario" id="modal_precio_unitario" value="0.00" placeholder="0.00"
                        class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#588157] focus:outline-none">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-[#2d3038]">
                <button type="button" onclick="document.getElementById('modal-nuevo-item').classList.add('hidden')" 
                    class="px-4 py-2 bg-[#181a20] border border-[#2d3038] text-white rounded hover:bg-[#252830]">
                    CANCELAR
                </button>
                <button type="submit" class="px-5 py-2 bg-[#588157] text-white font-extrabold uppercase rounded hover:bg-[#a3b18a] hover:text-[#1d2e26] transition-colors">
                    GUARDAR EN ALMACÉN
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDICIÓN DE ÍTEM EN ALMACÉN -->
<div id="modal-editar-item" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 max-w-md w-full space-y-4 font-mono text-xs rounded text-white shadow-2xl">
        <div class="flex items-center justify-between pb-3 border-b border-[#2d3038]">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 fill-current text-[#a3b18a]" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                <h3 id="titulo_modal_editar" class="text-sm font-bold uppercase tracking-wider">EDITAR ÍTEM DE ALMACÉN</h3>
            </div>
            <button type="button" onclick="document.getElementById('modal-editar-item').classList.add('hidden')" class="text-[#8a8e9b] hover:text-white">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>

        <form id="form-editar-item" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="tipo" id="edit_tipo_item">

            <div>
                <label id="label_edit_nombre" class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Nombre *</label>
                <input type="text" name="nombre" id="edit_nombre_item" required
                    class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#588157] focus:outline-none">
            </div>

            <div id="wrapper_edit_precio">
                <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Valor / Precio Unitario ($) *</label>
                <input type="number" step="0.01" min="0" name="precio_unitario" id="edit_precio_item"
                    class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#588157] focus:outline-none">
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-[#2d3038]">
                <button type="button" onclick="document.getElementById('modal-editar-item').classList.add('hidden')" 
                    class="px-4 py-2 bg-[#181a20] border border-[#2d3038] text-white rounded hover:bg-[#252830]">
                    CANCELAR
                </button>
                <button type="submit" class="px-5 py-2 bg-[#588157] text-white font-extrabold uppercase rounded hover:bg-[#a3b18a] hover:text-[#1d2e26] transition-colors">
                    GUARDAR CAMBIOS
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

function abrirModalEditar(btn) {
    const id = btn.getAttribute('data-id');
    const nombre = btn.getAttribute('data-nombre');
    const precio = btn.getAttribute('data-precio');
    const tipo = btn.getAttribute('data-tipo');

    const form = document.getElementById('form-editar-item');
    form.action = "{{ url('/almacen/item') }}/" + id;

    document.getElementById('edit_tipo_item').value = tipo;
    document.getElementById('edit_nombre_item').value = nombre;

    const wrapperPrecio = document.getElementById('wrapper_edit_precio');
    const inputPrecio = document.getElementById('edit_precio_item');
    const titulo = document.getElementById('titulo_modal_editar');
    const labelNombre = document.getElementById('label_edit_nombre');

    if (tipo === 'herramienta') {
        titulo.textContent = 'EDITAR NOMBRE DE HERRAMIENTA';
        labelNombre.textContent = 'Nombre de la Herramienta *';
        wrapperPrecio.classList.add('hidden');
        inputPrecio.required = false;
        inputPrecio.value = '0.00';
    } else {
        titulo.textContent = 'EDITAR REFACCIÓN Y VALOR';
        labelNombre.textContent = 'Ítem / Refacción *';
        wrapperPrecio.classList.remove('hidden');
        inputPrecio.required = true;
        inputPrecio.value = parseFloat(precio).toFixed(2);
    }

    document.getElementById('modal-editar-item').classList.remove('hidden');
}
</script>
@endsection