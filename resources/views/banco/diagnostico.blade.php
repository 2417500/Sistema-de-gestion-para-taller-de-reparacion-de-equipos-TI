@extends('layouts.private')

@section('title', 'Hoja de Diagnóstico')
@section('page-title', 'Banco Técnico // Hoja de Trabajo')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 font-mono text-xs">
    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038]">
        <div class="flex justify-between items-center pb-4 border-b border-[#2d3038] mb-6">
            <div>
                <h2 class="text-base font-bold text-[#ffb800] uppercase">
                    ORDEN: {{ $orden->codigo_servicio }}
                </h2>
                <p class="text-[#8a8e9b]">DISPOSITIVO: {{ $orden->marca }} {{ $orden->modelo }} | S/N: {{ $orden->numero_serie }}</p>
            </div>
            <span class="px-3 py-1 bg-[#1d212b] border border-[#ffb800]/40 text-[#ffb800] font-bold rounded uppercase">
                ESTADO: {{ $orden->estado }}
            </span>
        </div>

        <div class="bg-[#16181f] p-4 rounded border border-[#2d3038] mb-6">
            <h3 class="text-[#8a8e9b] uppercase font-bold mb-1">Falla Reportada en Recepción:</h3>
            <p class="text-white">{{ $orden->falla_reportada }}</p>
        </div>

        <form action="{{ url('/banco-tecnico/cotizar/' . $orden->id) }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block font-bold uppercase text-[#ffb800] mb-2">Diagnóstico Técnico Detallado</label>
                <textarea name="diagnostico_tecnico" rows="4" required placeholder="Ingresa pruebas realizadas, componentes dañados y solución propuesta..." class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white focus:border-[#ffb800] focus:outline-none">{{ $orden->diagnostico ?? '' }}</textarea>
            </div>

            <!-- SELECTOR DE ALMACÉN & REFACCIONES (MÚLTIPLES SELECCIONES) -->
            <div class="bg-[#16181f] p-4 rounded border border-[#2d3038] space-y-4">
                <div class="flex justify-between items-center border-b border-[#2d3038] pb-2">
                    <h3 class="text-[#ffb800] font-bold uppercase">Solicitud de Refacciones a Almacén:</h3>
                    <button type="button" id="btn_add_refaccion" class="px-3 py-1 bg-[#ffb800]/20 text-[#ffb800] border border-[#ffb800]/40 font-bold rounded hover:bg-[#ffb800]/30 transition-colors">
                        + AGREGAR OTRA REFACCIÓN
                    </button>
                </div>
                
                <div id="contenedor_refacciones" class="space-y-3">
                    <div class="fila-refaccion grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                        <div class="sm:col-span-8">
                            <label class="block text-[#8a8e9b] mb-1 font-bold uppercase">Refacción requerida:</label>
                            <select name="items_id[]" onchange="calcularTotal()" class="select_item w-full bg-[#0d0f12] border border-[#2d3038] rounded p-2.5 text-white focus:border-[#ffb800]">
                                <option value="" data-precio="0">-- Sin refacción física adicional --</option>
                                @foreach($inventario ?? [] as $item)
                                    <option value="{{ $item->id }}" data-precio="{{ $item->precio_unitario }}">
                                        [{{ strtoupper($item->tipo) }}] {{ $item->nombre }} (Stock: {{ $item->stock_actual }}) - ${{ number_format($item->precio_unitario, 2) }} MXN
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-[#8a8e9b] mb-1 font-bold uppercase">Cantidad:</label>
                            <input type="number" name="cantidades_items[]" value="1" min="1" oninput="calcularTotal()" class="cant_item w-full bg-[#0d0f12] border border-[#2d3038] rounded p-2.5 text-white focus:border-[#ffb800]">
                        </div>

                        <div class="sm:col-span-1 text-right">
                            <button type="button" onclick="eliminarFila(this)" class="btn_eliminar hidden w-full py-2.5 bg-red-500/20 text-red-400 border border-red-500/40 rounded hover:bg-red-500/30 font-bold">
                                ✕
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-[#2d3038] pt-6">
                <div>
                    <label class="block font-bold uppercase text-[#8a8e9b] mb-2">Costo Mano de Obra ($)</label>
                    <input type="number" step="0.01" name="costo_mano_obra" id="costo_mano_obra" required value="{{ $orden->costo_mano_obra ?? 500 }}" oninput="calcularTotal()" class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white focus:border-[#ffb800] focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold uppercase text-[#8a8e9b] mb-2">Costo Refacciones / Insumos ($)</label>
                    <input type="number" step="0.01" name="costo_refacciones" id="costo_refacciones" readonly value="{{ $orden->costo_refacciones ?? 0 }}" class="w-full bg-[#0d0f12] border border-[#2d3038] rounded p-3 text-[#ffb800] font-bold focus:outline-none">
                </div>
            </div>

            <div class="bg-[#1d212b] p-4 rounded border border-[#ffb800]/20 space-y-2">
                <div class="flex justify-between text-white font-bold">
                    <span>COSTO TOTAL COTIZADO:</span>
                    <span id="txt_total" class="text-[#ffb800]">$0.00 MXN</span>
                </div>
                <div class="flex justify-between text-[#8a8e9b] text-xs">
                    <span>ANTICIPO MÍNIMO REQUERIDO (50%):</span>
                    <span id="txt_anticipo" class="text-emerald-400 font-bold">$0.00 MXN</span>
                </div>
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-[#2d3038]">
                <a href="{{ route('banco.index') }}" class="text-[#8a8e9b] hover:text-white">← Volver al Banco</a>
                <button type="submit" class="px-6 py-3 bg-[#ffb800] text-[#0d0f12] font-bold uppercase tracking-wider hover:bg-white transition-colors">
                    Generar Cotización y Enviar al Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('btn_add_refaccion').addEventListener('click', function() {
    const contenedor = document.getElementById('contenedor_refacciones');
    const primeraFila = contenedor.querySelector('.fila-refaccion');
    const nuevaFila = primeraFila.cloneNode(true);
    
    // Limpiar campos de la nueva fila
    nuevaFila.querySelector('.select_item').value = "";
    nuevaFila.querySelector('.cant_item').value = 1;
    
    // Mostrar el botón eliminar en filas secundarias
    const btnEliminar = nuevaFila.querySelector('.btn_eliminar');
    btnEliminar.classList.remove('hidden');
    
    contenedor.appendChild(nuevaFila);
    calcularTotal();
});

function eliminarFila(btn) {
    const fila = btn.closest('.fila-refaccion');
    fila.remove();
    calcularTotal();
}

function calcularTotal() {
    let acumuladoRefacciones = 0;
    const filas = document.querySelectorAll('.fila-refaccion');

    filas.forEach(fila => {
        const select = fila.querySelector('.select_item');
        const cantidadInput = fila.querySelector('.cant_item');
        
        const selectedOption = select.options[select.selectedIndex];
        const precioUnitario = parseFloat(selectedOption.getAttribute('data-precio')) || 0;
        const cantidad = parseInt(cantidadInput.value) || 1;

        acumuladoRefacciones += (precioUnitario * cantidad);
    });

    document.getElementById('costo_refacciones').value = acumuladoRefacciones.toFixed(2);

    const mo = parseFloat(document.getElementById('costo_mano_obra').value) || 0;
    const total = mo + acumuladoRefacciones;
    const anticipo = total * 0.50;

    document.getElementById('txt_total').innerText = '$' + total.toFixed(2) + ' MXN';
    document.getElementById('txt_anticipo').innerText = '$' + anticipo.toFixed(2) + ' MXN';
}

document.addEventListener("DOMContentLoaded", function() {
    calcularTotal();
});
</script>
@endsection