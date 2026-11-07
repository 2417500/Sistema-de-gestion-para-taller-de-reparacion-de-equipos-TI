@extends('layouts.private')

@section('title', 'Petición de Herramientas')
@section('page-title', 'Banco Técnico // Petición de Herramientas')

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

    <!-- CABECERA DE INSTRUCCIÓN -->
    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038] flex justify-between items-center">
        <div>
            <h1 class="text-white text-base font-bold uppercase tracking-wider">GESTIÓN DE HERRAMIENTAS // {{ Auth::user()->name }}</h1>
            <p class="text-[#8a8e9b] text-xs mt-1">Solicita instrumental de trabajo a Almacén y gestiona los préstamos en uso.</p>
        </div>
    </div>

    <!-- FORMULARIO DE NUEVA PETICIÓN MÚLTIPLE DE HERRAMIENTAS -->
    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038] space-y-4">
        <div class="flex justify-between items-center border-b border-[#2d3038] pb-3">
            <h2 class="text-white font-bold text-sm uppercase flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffb800] text-base">handyman</span>
                <span>1. NUEVA PETICIÓN DE HERRAMIENTAS (MÚLTIPLE)</span>
            </h2>
            <button type="button" id="btn_add_herramienta" class="px-3 py-1 bg-[#ffb800]/20 text-[#ffb800] border border-[#ffb800]/40 font-bold rounded hover:bg-[#ffb800]/30 transition-colors text-[10px]">
                + AGREGAR OTRA HERRAMIENTA
            </button>
        </div>

        <form action="{{ route('banco.herramientas.solicitar') }}" method="POST" class="space-y-4">
            @csrf
            
            <div id="contenedor_herramientas" class="space-y-3">
                <div class="fila-herramienta grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                    <div class="sm:col-span-8">
                        <label class="block text-[#8a8e9b] mb-1 font-bold uppercase text-[10px]">SELECCIONAR HERRAMIENTA:</label>
                        <select name="items_id[]" required class="select_herramienta w-full bg-[#0d0f12] border border-[#2d3038] rounded p-2.5 text-white focus:border-[#ffb800]">
                            <option value="">-- Seleccione del Catálogo --</option>
                            @foreach($catHerramientas as $h)
                                <option value="{{ $h->id }}">
                                    {{ $h->nombre }} (Stock Disp: {{ $h->stock_actual }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-[#8a8e9b] mb-1 font-bold uppercase text-[10px]">CANTIDAD:</label>
                        <input type="number" name="cantidades[]" value="1" min="1" required class="cant_herramienta w-full bg-[#0d0f12] border border-[#2d3038] rounded p-2.5 text-white focus:border-[#ffb800]">
                    </div>

                    <div class="sm:col-span-1 text-right">
                        <button type="button" onclick="eliminarFilaHerramienta(this)" class="btn_eliminar_h hidden w-full py-2.5 bg-red-500/20 text-red-400 border border-red-500/40 rounded hover:bg-red-500/30 font-bold">
                            ✕
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-[#2d3038] text-right">
                <button type="submit" class="px-6 py-2.5 bg-[#ffb800] text-[#0d0f12] font-bold uppercase tracking-wider hover:bg-white transition-colors">
                    ENVIAR PETICIÓN A ADMIN
                </button>
            </div>
        </form>
    </div>

    <!-- ESTADO DE PETICIONES Y CONTROL DE PRÉSTAMOS -->
    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038] space-y-4">
        <h2 class="text-white font-bold text-sm uppercase flex items-center gap-2 border-b border-[#2d3038] pb-3">
            <span class="material-symbols-outlined text-[#ffb800] text-base">fact_check</span>
            <span>2. ESTADO DE PETICIONES Y CONTROL DE PRÉSTAMOS</span>
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#182238] text-white uppercase text-[10px] border-b border-[#2d3038]">
                        <th class="p-3">HERRAMIENTA</th>
                        <th class="p-3">CANTIDAD</th>
                        <th class="p-3">ESTADO ACTUAL</th>
                        <th class="p-3 text-right">ACCIONES TÉCNICO</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d3038] text-white">
                    @forelse($misPrestamos as $p)
                        <tr class="hover:bg-[#16181f]/50 transition-colors">
                            <td class="p-3 text-white font-bold">{{ $p->herramienta_nombre }}</td>
                            <td class="p-3 text-[#ffb800] font-bold">{{ $p->cantidad }} pza(s)</td>
                            <td class="p-3">
                                @if($p->estado === 'PENDIENTE_ADMIN')
                                    <span class="px-2 py-0.5 bg-amber-950/60 border border-amber-500/50 text-amber-400 text-[10px] uppercase font-bold rounded">ESPERANDO APROBACIÓN ADMIN</span>
                                @elseif($p->estado === 'AUTORIZADO_ADMIN')
                                    <span class="px-2 py-0.5 bg-blue-950/60 border border-blue-500/50 text-blue-400 text-[10px] uppercase font-bold rounded">AUTORIZADO / EN ESPERA DE ALMACÉN</span>
                                @elseif($p->estado === 'ENVIADO_TECNICO')
                                    <span class="px-2 py-0.5 bg-indigo-950/60 border border-indigo-500/50 text-indigo-400 text-[10px] uppercase font-bold rounded">ENVIADO POR ALMACÉN</span>
                                @elseif($p->estado === 'EN_USO')
                                    <span class="px-2 py-0.5 bg-emerald-950/60 border border-emerald-500/50 text-emerald-400 text-[10px] uppercase font-bold rounded">HERRAMIENTA EN USO</span>
                                @endif
                            </td>
                            <td class="p-3 flex gap-2 justify-end">
                                <!-- BOTÓN 1: RECIBIR HERRAMIENTAS -->
                                @if($p->estado === 'ENVIADO_TECNICO')
                                    <form action="{{ route('banco.herramientas.recibir', $p->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-3 py-1 text-[10px] uppercase rounded transition-colors">
                                            RECIBIR HERRAMIENTAS
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="bg-[#1a1c23] text-[#5c606e] font-bold px-3 py-1 text-[10px] uppercase border border-[#2d3038] rounded cursor-not-allowed">
                                        RECIBIR HERRAMIENTAS
                                    </button>
                                @endif

                                <!-- BOTÓN 2: REGRESAR HERRAMIENTAS -->
                                @if($p->estado === 'EN_USO')
                                    <form action="{{ route('banco.herramientas.devolver', $p->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white font-bold px-3 py-1 text-[10px] uppercase rounded transition-colors">
                                            REGRESAR HERRAMIENTAS
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="bg-[#1a1c23] text-[#5c606e] font-bold px-3 py-1 text-[10px] uppercase border border-[#2d3038] rounded cursor-not-allowed">
                                        REGRESAR HERRAMIENTAS
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-[#8a8e9b] uppercase">No tienes peticiones activas de herramientas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('btn_add_herramienta').addEventListener('click', function() {
    const contenedor = document.getElementById('contenedor_herramientas');
    const primeraFila = contenedor.querySelector('.fila-herramienta');
    const nuevaFila = primeraFila.cloneNode(true);
    
    // Limpiar selección y valor de la nueva fila
    nuevaFila.querySelector('.select_herramienta').value = "";
    nuevaFila.querySelector('.cant_herramienta').value = 1;
    
    // Mostrar el botón de eliminar fila en las filas dinámicas
    const btnEliminar = nuevaFila.querySelector('.btn_eliminar_h');
    btnEliminar.classList.remove('hidden');
    
    contenedor.appendChild(nuevaFila);
});

function eliminarFilaHerramienta(btn) {
    const fila = btn.closest('.fila-herramienta');
    fila.remove();
}
</script>
@endsection