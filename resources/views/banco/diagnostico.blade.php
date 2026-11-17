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

        <form action="{{ url('/banco-tecnico/cotizar/' . $orden->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- DIAGNÓSTICO TÉCNICO DETALLADO -->
            <div>
                <label class="block font-bold uppercase text-[#ffb800] mb-2">Diagnóstico Técnico Detallado</label>
                <textarea name="diagnostico_tecnico" rows="4" required placeholder="Ingresa pruebas realizadas, componentes dañados y solución propuesta..." class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white focus:border-[#ffb800] focus:outline-none">{{ $orden->diagnostico ?? '' }}</textarea>
            </div>

            <!-- OBSERVACIONES TÉCNICOS + EVIDENCIAS FOTOGRÁFICAS -->
            <div class="space-y-3">
                <div>
                    <label class="block font-bold uppercase text-[#ffb800] mb-2">Observaciones Técnicos</label>
                    <textarea name="observaciones_taller" rows="3" placeholder="Ingresa observaciones físicas de cómo llegó el dispositivo al taller (ej. teclas faltantes, golpes, tornillos ausentes, detalles distintos al ingreso)..." class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white focus:border-[#ffb800] focus:outline-none">{{ $orden->observaciones_taller ?? '' }}</textarea>
                </div>

                <div>
                    <label class="block font-bold uppercase text-[#8a8e9b] mb-2">Evidencias / Fotos del Técnico (Visible para el Cliente)</label>
                    <input type="file" name="fotos_diagnostico[]" id="fotos_diagnostico" multiple accept="image/*" onchange="previsualizarEvidencias(this)" class="w-full bg-[#16181f] border border-[#2d3038] rounded p-2.5 text-[#c4c6cf] file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-bold file:bg-[#588157] file:text-white hover:file:bg-[#a3b18a] hover:file:text-black cursor-pointer">
                    <div id="preview_evidencias" class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-3 hidden"></div>
                </div>
            </div>

            <!-- SELECTOR DE ALMACÉN & REFACCIONES -->
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
                <button type="submit" class="px-6 py-3 bg-[#588157] text-white font-bold uppercase rounded hover:bg-[#a3b18a] hover:text-black transition-colors">
                    GENERAR COTIZACIÓN Y ENVIAR AL CLIENTE
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function calcularTotal() {
        let totalRefacciones = 0;
        const filas = document.querySelectorAll('.fila-refaccion');

        filas.forEach(fila => {
            const select = fila.querySelector('.select_item');
            const cantInput = fila.querySelector('.cant_item');
            const opcion = select.options[select.selectedIndex];
            const precio = parseFloat(opcion.getAttribute('data-precio') || 0);
            const cantidad = parseInt(cantInput.value || 0);

            if (select.value !== '') {
                totalRefacciones += (precio * cantidad);
            }
        });

        const manoObra = parseFloat(document.getElementById('costo_mano_obra').value || 0);
        const total = totalRefacciones + manoObra;
        const anticipo = total * 0.50;

        document.getElementById('costo_refacciones').value = totalRefacciones.toFixed(2);
        document.getElementById('txt_total').innerText = '$' + total.toFixed(2) + ' MXN';
        document.getElementById('txt_anticipo').innerText = '$' + anticipo.toFixed(2) + ' MXN';
    }

    document.getElementById('btn_add_refaccion').addEventListener('click', function () {
        const contenedor = document.getElementById('contenedor_refacciones');
        const primeraFila = contenedor.querySelector('.fila-refaccion');
        const nuevaFila = primeraFila.cloneNode(true);

        nuevaFila.querySelector('.select_item').selectedIndex = 0;
        nuevaFila.querySelector('.cant_item').value = 1;
        nuevaFila.querySelector('.btn_eliminar').classList.remove('hidden');

        contenedor.appendChild(nuevaFila);
        calcularTotal();
    });

    function eliminarFila(btn) {
        const fila = btn.closest('.fila-refaccion');
        fila.remove();
        calcularTotal();
    }

    function previsualizarEvidencias(input) {
        const preview = document.getElementById('preview_evidencias');
        preview.innerHTML = '';

        if (input.files && input.files.length > 0) {
            preview.classList.remove('hidden');
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const div = document.createElement('div');
                    div.className = 'border border-[#2d3038] rounded overflow-hidden bg-[#0d0f12]';
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-24 object-cover">`;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        } else {
            preview.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', calcularTotal);
</script>
@endsection