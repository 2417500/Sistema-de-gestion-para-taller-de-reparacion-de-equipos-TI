@extends('layouts.private')

@section('title', 'Detalle de Orden y Liquidación')
@section('page-title', 'Recepción // Cobro & Entrega de Dispositivo')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 font-mono text-xs">
    
    {{-- ALERTAS DE SISTEMA (ÉXITO / ERROR) --}}
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/40 text-emerald-400 p-4 rounded-lg flex items-center gap-3 shadow-lg">
            <span class="material-symbols-outlined text-xl">check_circle</span>
            <div>
                <h4 class="font-bold uppercase text-sm">Operación Exitosa</h4>
                <p class="text-xs text-emerald-300/80">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/40 text-red-400 p-4 rounded-lg flex items-center gap-3 shadow-lg">
            <span class="material-symbols-outlined text-xl">warning</span>
            <div>
                <h4 class="font-bold uppercase text-sm">Error en el Proceso</h4>
                <p class="text-xs text-red-300/80">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- TARJETA PRINCIPAL DE LIQUIDACIÓN Y DETALLE --}}
    <div class="noir-card bg-[#111318] p-6 sm:p-8 border border-[#2d3038] rounded-xl shadow-2xl space-y-6">
        
        <!-- ENCABEZADO SUPERIOR DE LA ORDEN DE SERVICIO -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-[#2d3038] gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#ffb800]">receipt_long</span>
                    <h2 class="text-lg font-bold text-[#ffb800] uppercase tracking-wider">
                        ORDEN DE SERVICIO: {{ $orden->codigo_servicio }}
                    </h2>
                </div>
                <p class="text-[#8a8e9b] text-xs">
                    DISPOSITIVO: <span class="text-white font-bold">{{ $orden->marca }} {{ $orden->modelo }}</span> | 
                    N° SERIE: <span class="text-white font-bold">{{ $orden->numero_serie }}</span>
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="text-[10px] text-[#8a8e9b] uppercase font-bold">ESTADO OPERATIVO:</span>
                <span class="px-3 py-1.5 bg-[#1d212b] border border-[#ffb800]/40 text-[#ffb800] font-bold rounded uppercase tracking-wide text-xs">
                    {{ str_replace('_', ' ', $orden->estado) }}
                </span>
            </div>
        </div>

        <!-- SECCIÓN 1: DATOS DEL CLIENTE Y REGISTRO EN RECEPCIÓN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-[#16181f] p-4 rounded-lg border border-[#2d3038] space-y-2">
                <h3 class="text-[#ffb800] uppercase font-bold text-xs flex items-center gap-1.5 border-b border-[#2d3038] pb-2">
                    <span class="material-symbols-outlined text-sm">person</span>
                    Información del Cliente
                </h3>
                <div class="space-y-1 pt-1 text-[#c4c6cf]">
                    <p><strong class="text-white">Nombre:</strong> {{ $orden->cliente_nombre ?? 'Cliente General' }}</p>
                    <p><strong class="text-white">Correo:</strong> {{ $orden->cliente_email ?? 'N/A' }}</p>
                    <p><strong class="text-white">Teléfono:</strong> {{ $orden->cliente_telefono ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="bg-[#16181f] p-4 rounded-lg border border-[#2d3038] space-y-2">
                <h3 class="text-[#ffb800] uppercase font-bold text-xs flex items-center gap-1.5 border-b border-[#2d3038] pb-2">
                    <span class="material-symbols-outlined text-sm">devices</span>
                    Especificaciones del Equipo
                </h3>
                <div class="space-y-1 pt-1 text-[#c4c6cf]">
                    <p><strong class="text-white">Tipo Dispositivo:</strong> {{ $orden->tipo_equipo ?? 'Laptop / PC' }}</p>
                    <p><strong class="text-white">Marca / Modelo:</strong> {{ $orden->marca }} {{ $orden->modelo }}</p>
                    <p><strong class="text-white">Número de Serie:</strong> {{ $orden->numero_serie }}</p>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: DETALLE DE FALLA Y DIAGNÓSTICO TÉCNICO -->
        <div class="bg-[#16181f] p-4 rounded-lg border border-[#2d3038] space-y-3">
            <h3 class="text-[#8a8e9b] uppercase font-bold text-xs flex items-center gap-1.5 border-b border-[#2d3038] pb-2">
                <span class="material-symbols-outlined text-sm">description</span>
                Reporte de Falla & Diagnóstico Técnico Registrado
            </h3>
            <div class="text-white leading-relaxed whitespace-pre-line bg-[#0d0f12] p-3 rounded border border-[#2d3038]">
                {{ $orden->falla_reportada }}
            </div>
        </div>

        <!-- SECCIÓN 3: DESGLOSE FINANCIERO Y RESUMEN DE LIQUIDACIÓN -->
        <div class="bg-[#1d212b] p-5 rounded-lg border border-[#ffb800]/30 space-y-4 shadow-inner">
            <div class="flex justify-between items-center border-b border-[#ffb800]/20 pb-2">
                <h3 class="text-[#ffb800] uppercase font-bold text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">payments</span>
                    Desglose Financiero y Cuenta Final
                </h3>
                <span class="text-[10px] text-[#8a8e9b] uppercase font-bold">Moneda Nacional (MXN)</span>
            </div>
            
            <div class="space-y-2">
                <div class="flex justify-between text-[#8a8e9b]">
                    <span>Costo Mano de Obra (Técnico):</span>
                    <span class="text-white font-bold">${{ number_format($orden->costo_mano_obra ?? 0, 2) }} MXN</span>
                </div>
                
                <div class="flex justify-between text-[#8a8e9b]">
                    <span>Costo Refacciones e Insumos (Almacén):</span>
                    <span class="text-white font-bold">${{ number_format($orden->costo_refacciones ?? 0, 2) }} MXN</span>
                </div>

                <div class="flex justify-between text-white font-bold text-sm pt-3 border-t border-[#2d3038]">
                    <span>COSTO TOTAL COTIZADO:</span>
                    <span class="text-[#ffb800] text-base">${{ number_format($orden->costo_total ?? 0, 2) }} MXN</span>
                </div>

                @php
                    $anticipoCalculado = $orden->anticipo ?? (($orden->costo_total ?? 0) * 0.50);
                    $saldoPendiente = ($orden->costo_total ?? 0) - $anticipoCalculado;
                @endphp

                <div class="flex justify-between text-emerald-400 font-bold">
                    <span>Anticipo Pagado al Recibir (50%):</span>
                    <span>-${{ number_format($anticipoCalculado, 2) }} MXN</span>
                </div>

                <div class="flex justify-between text-[#ffb800] font-bold text-base pt-3 border-t border-[#ffb800]/40">
                    <span>SALDO PENDIENTE A COBRAR EN LIQUIDACIÓN:</span>
                    <span class="text-emerald-400 text-lg">${{ number_format($saldoPendiente, 2) }} MXN</span>
                </div>
            </div>
        </div>

        <!-- BOTONES INFERIORES Y ACCIONES DE ENTREGA -->
        <div class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-[#2d3038] gap-4">
            <a href="{{ route('cotizaciones.index') }}" 
               class="w-full sm:w-auto px-5 py-3 bg-[#16181f] hover:bg-[#1d212b] text-[#8a8e9b] hover:text-white border border-[#2d3038] rounded-lg font-bold transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Volver al Menú de Cotizaciones</span>
            </a>

            <div class="w-full sm:w-auto flex items-center justify-end">
                @if(in_array(strtoupper($orden->estado), ['TERMINADO', 'EN_PROCESO', 'COTIZACION_ENVIADA', 'PENDIENTE']))
                    {{-- ACCIÓN DE LIQUIDAR Y ENTREGAR --}}
                    <form action="{{ route('recepcion.entregar', $orden->id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" 
                                class="w-full sm:w-auto px-8 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold uppercase tracking-wider rounded-lg transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-950/50 cursor-pointer">
                            <span class="material-symbols-outlined">point_of_sale</span>
                            <span>COBRAR Y ENTREGAR DISPOSITIVO</span>
                        </button>
                    </form>
                @elseif(in_array(strtoupper($orden->estado), ['ENTREGADO']))
                    {{-- DESCARGA DE COMPROBANTE CON EVIDENCIAS FOTOGRÁFICAS --}}
                    <a href="{{ route('entrega.pdf', $orden->id) }}" 
                       class="w-full sm:w-auto px-8 py-3.5 bg-[#ffb800] hover:bg-white text-[#0d0f12] font-bold uppercase tracking-wider rounded-lg transition-all flex items-center justify-center gap-2 shadow-lg shadow-[#ffb800]/20">
                        <span class="material-symbols-outlined">picture_as_pdf</span>
                        <span>DESCARGAR NOTA DE ENTREGA (PDF)</span>
                    </a>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection