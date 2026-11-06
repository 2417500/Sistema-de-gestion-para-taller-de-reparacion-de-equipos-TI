@extends('layouts.private')

@section('title', 'Revisión de Cotización')
@section('page-title', 'Presupuesto de Servicio Técnico')

@section('content')
<div class="max-w-3xl mx-auto font-mono text-xs space-y-6">
    
    @if (session('success'))
        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="noir-card bg-[#111318] p-6 border border-[#2d3038] space-y-6">
        <div class="flex justify-between items-center pb-4 border-b border-[#2d3038]">
            <div>
                <h2 class="text-base font-bold text-[#ffb800] uppercase">ORDEN DE SERVICIO: {{ $orden->codigo_servicio }}</h2>
                <p class="text-[#8a8e9b]">DISPOSITIVO: {{ $orden->marca }} {{ $orden->modelo }} | S/N: {{ $orden->numero_serie }}</p>
            </div>
            <span class="px-3 py-1 bg-[#1d212b] border border-[#ffb800]/40 text-[#ffb800] font-bold rounded uppercase">
                ESTADO: {{ $orden->estado }}
            </span>
        </div>

        <div class="bg-[#16181f] p-4 rounded border border-[#2d3038] space-y-2">
            <h3 class="text-[#ffb800] font-bold uppercase">Diagnóstico y Reporte Técnico:</h3>
            <p class="text-white text-xs leading-relaxed whitespace-pre-line">{{ $orden->falla_reportada ?? 'Sin reporte capturado.' }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-[#0d0f12] p-4 rounded border border-[#2d3038]">
            <div>
                <span class="text-[#8a8e9b] block mb-1">COSTO MANO DE OBRA:</span>
                <span class="text-white font-bold text-sm">${{ number_format($orden->costo_mano_obra ?? 0, 2) }} MXN</span>
            </div>
            <div>
                <span class="text-[#8a8e9b] block mb-1">REFACCIONES E INSUMOS:</span>
                <span class="text-white font-bold text-sm">${{ number_format($orden->costo_refacciones ?? 0, 2) }} MXN</span>
            </div>
            <div class="col-span-2 pt-3 border-t border-[#2d3038] flex justify-between items-center">
                <span class="text-white font-bold">TOTAL NETO REPARACIÓN:</span>
                <span class="text-[#ffb800] text-base font-bold">${{ number_format($orden->costo_total ?? 0, 2) }} MXN</span>
            </div>
            <div class="col-span-2 flex justify-between items-center text-emerald-400 font-bold">
                <span>PAGO DE ANTICIPO REGISTRADO (50%):</span>
                <span>${{ number_format(($orden->costo_total ?? 0) * 0.5, 2) }} MXN</span>
            </div>
            <div class="col-span-2 flex justify-between items-center text-sky-400 font-bold border-t border-[#2d3038] pt-2">
                <span>SALDO PENDIENTE A LIQUIDAR (50%):</span>
                <span>${{ number_format(($orden->costo_total ?? 0) * 0.5, 2) }} MXN</span>
            </div>
        </div>

        @php
            $rolActual = strtolower(trim(Auth::user()->role->nombre ?? 'cliente'));
            $esCliente = str_contains($rolActual, 'client');
            $estadoNormal = strtolower($orden->estado);
        @endphp

        {{-- VISTA PARA CLIENTE --}}
        @if($esCliente)
            @if(in_array($estadoNormal, ['en_proceso', 'cotizado', 'diagnosticado']) || ($orden->costo_total > 0 && $estadoNormal !== 'terminado' && $estadoNormal !== 'entregado'))
                <form action="{{ route('cotizacion.responder') }}" method="POST" class="space-y-4 pt-4 border-t border-[#2d3038]">
                    @csrf
                    <input type="hidden" name="orden_id" value="{{ $orden->id }}">
                    
                    <label class="block text-[#8a8e9b] font-bold uppercase">Comentario o Nota para el Taller (Opcional):</label>
                    <textarea name="nota_cliente" rows="3" placeholder="Ingresa dudas, solicitudes de ajuste o aclaraciones..." class="w-full bg-[#16181f] border border-[#2d3038] rounded p-3 text-white focus:border-[#ffb800] focus:outline-none"></textarea>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" name="accion" value="aceptar" class="flex-1 py-3 bg-emerald-600 text-white font-bold rounded hover:bg-emerald-500 transition-colors uppercase">
                            ✓ Aceptar Cotización
                        </button>
                        <button type="submit" name="accion" value="ajustar" class="flex-1 py-3 bg-amber-600 text-white font-bold rounded hover:bg-amber-500 transition-colors uppercase">
                            💬 Solicitar Ajuste
                        </button>
                        <button type="submit" name="accion" value="rechazar" class="flex-1 py-3 bg-rose-600 text-white font-bold rounded hover:bg-rose-500 transition-colors uppercase">
                            ✕ Rechazar Cotización
                        </button>
                    </div>
                </form>
            @elseif($estadoNormal === 'terminado')
                <div class="p-4 bg-emerald-950/40 border border-emerald-500/40 rounded text-center text-emerald-300 font-bold uppercase">
                    ¡Tu equipo está listo! Puedes pasar a la sucursal para liquidar el saldo restante de ${{ number_format(($orden->costo_total ?? 0) * 0.5, 2) }} MXN y recoger tu dispositivo.
                </div>
            @endif
        @else
            {{-- SECCIÓN DE CAJA Y ENTREGA PARA RECEPCIÓN / ADMIN --}}
            <div class="pt-4 border-t border-[#2d3038] space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-[#ffb800] font-bold uppercase">Módulo de Caja y Recepción:</h3>
                    
                    <!-- Botón para descargar siempre el PDF de Recepción -->
                    <a href="{{ route('recepcion.pdf', $orden->id) }}" class="px-3 py-2 bg-[#2d3038] hover:bg-gray-700 text-white font-bold uppercase rounded transition-colors flex items-center gap-2 text-[10px]">
                        <span class="material-symbols-outlined text-sm">download</span>
                        <span>PDF Ticket Recepción</span>
                    </a>
                </div>
                
                @if($estadoNormal === 'terminado')
                    <div class="p-4 bg-[#16181f] border border-[#2d3038] rounded flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div>
                            <span class="text-white font-bold block">LIQUIDACIÓN FINAL Y ENTREGA DE DISPOSITIVO</span>
                            <span class="text-[#8a8e9b] text-xs">Monto a cobrar para finiquito: <strong class="text-sky-400">${{ number_format(($orden->costo_total ?? 0) * 0.5, 2) }} MXN</strong></span>
                        </div>

                        <form action="{{ route('recepcion.entregar', $orden->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-3 bg-sky-600 hover:bg-sky-500 text-white font-mono font-bold uppercase rounded transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">inventory_check</span>
                                <span>Cobrar ${{ number_format(($orden->costo_total ?? 0) * 0.5, 2) }} y Entregar</span>
                            </button>
                        </form>
                    </div>
                @elseif($estadoNormal === 'entregado')
                    <div class="p-4 bg-emerald-950/40 border border-emerald-500/40 rounded flex flex-col sm:flex-row items-center justify-between gap-4 text-emerald-300 font-bold uppercase">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined">verified</span>
                            <span>SERVICIO COMPLETADO Y EQUIPO ENTREGADO</span>
                        </div>
                        
                        <a href="{{ route('entrega.pdf', $orden->id) }}" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-black font-bold uppercase rounded transition-colors flex items-center gap-2 text-xs">
                            <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
                            <span>Descargar PDF Nota de Entrega</span>
                        </a>
                    </div>
                @else
                    <div class="p-4 bg-[#16181f] border border-[#2d3038] rounded flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div>
                            <span class="text-white font-bold block">REGISTRAR PAGO DE ANTICIPO</span>
                            <span class="text-[#8a8e9b] text-xs">Monto a recibir en caja: <strong class="text-emerald-400">${{ number_format(($orden->costo_total ?? 0) * 0.5, 2) }} MXN</strong></span>
                        </div>

                        <form action="{{ route('recepcion.cobrar', $orden->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-mono font-bold uppercase rounded transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">payments</span>
                                <span>Cobrar ${{ number_format(($orden->costo_total ?? 0) * 0.5, 2) }} MXN</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>
@endsection