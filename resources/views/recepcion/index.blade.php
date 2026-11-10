@extends('layouts.private')

@section('title', 'Recepción de Equipos')
@section('page-title', 'Nueva Orden de Recepción')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 font-mono text-xs">
    
    @if (session('error'))
        <div class="bg-rose-950/80 border border-rose-500/50 text-rose-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">warning</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('recepcion.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 1. DATOS DEL CLIENTE -->
        <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4 rounded">
            <div class="flex items-center justify-between pb-3 border-b border-[#2d3038]">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#ffb800]">person_search</span>
                    <h2 class="text-sm font-bold text-white uppercase tracking-wider">1. INFORMACIÓN DEL CLIENTE</h2>
                </div>
                <span id="cliente-status" class="hidden px-2 py-0.5 text-[10px] font-bold border rounded uppercase bg-emerald-950/80 border-emerald-500/50 text-emerald-400">
                    CLIENTE EXISTENTE ENCONTRADO
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Correo Electrónico *</label>
                    <input type="email" id="cliente_email" name="cliente_email" required value="{{ old('cliente_email') }}"
                        class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none transition-colors"
                        placeholder="ejemplo@correo.com">
                </div>

                <div>
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Nombre Completo *</label>
                    <input type="text" id="cliente_name" name="cliente_name" required value="{{ old('cliente_name') }}"
                        class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none transition-colors"
                        placeholder="Nombre y Apellidos">
                </div>

                <div>
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Teléfono de Contacto *</label>
                    <input type="text" id="cliente_telefono" name="cliente_telefono" required value="{{ old('cliente_telefono') }}"
                        class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none transition-colors"
                        placeholder="5512345678">
                </div>
            </div>
        </div>

        <!-- 2. DATOS DEL EQUIPO Y PRIORIDAD -->
        <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4 rounded">
            <div class="flex items-center gap-2 pb-3 border-b border-[#2d3038]">
                <span class="material-symbols-outlined text-[#ffb800]">laptop_mac</span>
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">2. DATOS DEL EQUIPO & PRIORIDAD</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Tipo de Equipo *</label>
                    <select name="tipo_equipo" required class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
                        <option value="Laptop">Laptop</option>
                        <option value="PC de Escritorio">PC de Escritorio</option>
                        <option value="All-in-One">All-in-One</option>
                        <option value="Consola">Consola</option>
                        <option value="Servidor">Servidor</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Marca *</label>
                    <input type="text" name="marca" required value="{{ old('marca') }}" placeholder="Dell, HP, Lenovo..."
                        class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
                </div>

                <div>
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Modelo *</label>
                    <input type="text" name="modelo" required value="{{ old('modelo') }}" placeholder="Inspiron 15, ThinkPad..."
                        class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
                </div>

                <div>
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Número de Serie / IMEI *</label>
                    <input type="text" name="numero_serie" required value="{{ old('numero_serie') }}" placeholder="SN-XXXXXX"
                        class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                <div>
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Tipo de Servicio *</label>
                    <select name="tipo_servicio" required class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
                        <option value="Diagnostico">Diagnóstico</option>
                        <option value="Reparacion">Reparación Directa</option>
                        <option value="Mantenimiento">Mantenimiento Preventivo</option>
                        <option value="Garantia">Garantía</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Nivel de Prioridad *</label>
                    <select name="prioridad" required class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
                        <option value="NORMAL">NORMAL (Estándar)</option>
                        <option value="URGENTE">URGENTE (Prioridad Alta)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Fecha Límite Sugerida *</label>
                    <input type="date" id="fecha_limite" name="fecha_limite" value="{{ date('Y-m-d', strtotime('+3 days')) }}" min="{{ date('Y-m-d') }}" required
                        class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Falla Reportada & Estado Físico Inicial *</label>
                <textarea name="falla_reportada" rows="3" required placeholder="Describe lo que reporta el cliente, rayones, golpes o detalles del equipo..."
                    class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">{{ old('falla_reportada') }}</textarea>
            </div>
        </div>

        <!-- 3. EVIDENCIA FOTOGRÁFICA DIVIDIDA -->
        <div class="noir-card bg-[#111318] border border-[#2d3038] p-6 space-y-4 rounded">
            <div class="flex items-center gap-2 pb-3 border-b border-[#2d3038]">
                <span class="material-symbols-outlined text-[#ffb800]">photo_camera</span>
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">3. EVIDENCIA FOTOGRÁFICA (ENTRADA)</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-white font-bold uppercase text-[11px]">Fotografías del Equipo (Carcasa, Pantalla, Serie)</label>
                    <input type="file" name="fotos_equipo[]" multiple accept="image/*"
                        class="w-full bg-[#181a20] border border-[#2d3038] text-[#8a8e9b] p-2 rounded file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-[#ffb800] file:text-[#0d0f12]">
                </div>

                <div class="space-y-2">
                    <label class="block text-white font-bold uppercase text-[11px]">Fotografías Extras (Cargador, Mochila, Funda)</label>
                    <input type="file" name="fotos_extras[]" multiple accept="image/*"
                        class="w-full bg-[#181a20] border border-[#2d3038] text-[#8a8e9b] p-2 rounded file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-[#ffb800] file:text-[#0d0f12]">
                </div>
            </div>
        </div>

        <!-- BOTÓN DE PROCESAR -->
        <div class="flex justify-end pt-2">
            <button type="submit" class="px-6 py-3 bg-[#ffb800] text-[#0d0f12] font-extrabold uppercase rounded hover:bg-white transition-colors flex items-center gap-2 shadow-lg">
                <span class="material-symbols-outlined text-base">print</span>
                <span>REGISTRAR & IMPRIMIR TICKET DE RECEPCIÓN</span>
            </button>
        </div>
    </form>
</div>

<!-- SCRIPT DE BÚSQUEDA Y AUTOCOMPLETADO EN TIEMPO REAL -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputEmail = document.getElementById('cliente_email');
    const inputNombre = document.getElementById('cliente_name');
    const inputTelefono = document.getElementById('cliente_telefono');
    const statusTag = document.getElementById('cliente-status');

    let timeout = null;

    function buscarCliente(valor) {
        clearTimeout(timeout);
        if (!valor || valor.trim().length < 2) {
            statusTag.classList.add('hidden');
            return;
        }

        timeout = setTimeout(() => {
            fetch(`{{ route('recepcion.buscarCliente') }}?term=${encodeURIComponent(valor)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.encontrado) {
                        if (inputEmail) inputEmail.value = data.email;
                        if (inputNombre) inputNombre.value = data.name;
                        if (inputTelefono) inputTelefono.value = data.telefono;
                        
                        statusTag.classList.remove('hidden');
                    } else {
                        statusTag.classList.add('hidden');
                    }
                })
                .catch(err => console.error("Error consultando cliente:", err));
        }, 350);
    }

    if (inputEmail) inputEmail.addEventListener('input', (e) => buscarCliente(e.target.value));
    if (inputNombre) inputNombre.addEventListener('input', (e) => buscarCliente(e.target.value));
    if (inputTelefono) inputTelefono.addEventListener('input', (e) => buscarCliente(e.target.value));
});
</script>
@endsection