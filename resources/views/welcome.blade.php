@extends('layouts.public')

@section('title', 'TecnoFix Solutions | SoporTec v4.2')

@section('content')
<div class="max-w-7xl mx-auto px-4 lg:px-8 py-8 space-y-12">
    <!-- HERO SECTION NOIR DE ALTO IMPACTO -->
    <div class="relative noir-card bg-surface-container-lowest p-8 md:p-12 amber-line overflow-hidden">
        <div class="max-w-3xl space-y-6 relative z-10">
            <!-- Aviso Protocolar Destacado -->
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-noir-blue border border-primary-container">
                <span class="w-2 h-2 bg-primary-container animate-ping rounded-full"></span>
                <span class="font-label-md text-label-md text-primary-container font-bold tracking-wider uppercase">
                    AVISO PROTOCOLAR: DIAGNÓSTICO GRATUITO $0
                </span>
            </div>

            <!-- Título Principal Grotesk -->
            <h1 class="font-headline-xl text-headline-xl font-bold tracking-tight text-white uppercase text-3xl md:text-4xl leading-tight">
                Ingeniería Diagnóstica de Alta Precisión para Hardware Enterprise y Crítico.
            </h1>

            <!-- Descripción -->
            <p class="font-body-lg text-body-lg text-tertiary-container max-w-2xl leading-relaxed text-on-surface-variant text-base">
                TecnoFix Solutions (SoporTec v4.2) opera con tolerancia cero al fallo. Análisis de esquemáticos a nivel microcomponente, trazabilidad de circuito cerrado y auditoría transparente para servidores, estaciones portátiles y terminales empresariales.
            </p>

            <!-- Acciones Principales -->
            <div class="pt-2 flex flex-wrap items-center gap-4">
                <a href="#seguimiento" class="px-6 py-3 bg-primary-container text-noir-black font-label-md text-xs font-bold uppercase tracking-wider border border-primary-container hover:bg-white hover:border-white transition-all active:translate-y-px amber-glow flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">search</span>
                    <span>Seguimiento de Ticket ($0 Diagnóstico)</span>
                </a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-noir-blue border border-outline-variant text-white font-label-md text-xs uppercase tracking-wider hover:border-primary-container hover:text-primary-container transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">dashboard</span>
                        <span>Ir a Mi Workbench (Panel)</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 bg-noir-blue border border-outline-variant text-white font-label-md text-xs uppercase tracking-wider hover:border-primary-container hover:text-primary-container transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">login</span>
                        <span>Acceso al Workbench Técnico</span>
                    </a>
                @endauth
            </div>
        </div>

        <!-- Decorador de fondo esquemático -->
        <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none select-none text-right pr-6 pb-4 hidden lg:block font-mono text-xs text-primary-container">
            <div>// ARCHITECTURE: NOIR-BENCH-4.2</div>
            <div>// TELEMETRY: 24-48 HRS LATENCY</div>
            <div>// STATUS: ALL SYSTEMS NORMAL</div>
        </div>
    </div>

    <!-- SECCIÓN: BUSCADOR EN VIVO DE ORDEN POR CÓDIGO O SERIAL -->
    <div id="seguimiento" class="noir-card bg-surface-container-lowest p-6 border-l-2 border-primary-container">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary-container text-2xl">qr_code_scanner</span>
            <div>
                <h2 class="text-headline-md font-bold text-white uppercase text-lg">Consulta Pública de Telemetría de Reparación</h2>
                <p class="text-on-surface-variant text-xs font-mono">Ingrese el código de servicio impreso en su comprobante o el número de serie de su equipo.</p>
            </div>
        </div>

        <form action="{{ url('/seguimiento') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant text-lg">search</span>
                <input type="text" name="codigo" value="{{ request('codigo', $codigo ?? '') }}" placeholder="Ej: ST-2026-0001 o Número de Serie (SN-LNV-XXXX)..." required
                       class="w-full pl-10 pr-4 py-2.5 bg-surface-container border border-outline-variant font-label-md text-sm text-white placeholder-on-surface-variant/40 focus:border-primary-container focus:ring-0 outline-none uppercase font-mono">
            </div>
            <button type="submit" class="px-6 py-2.5 bg-primary-container text-noir-black font-label-md text-xs font-bold uppercase tracking-wider hover:bg-white transition-colors amber-glow flex items-center justify-center gap-2">
                <span>Rastrear Estado</span>
            </button>
        </form>

        @if(session('success'))
            <div class="mt-4 p-4 bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 font-mono text-xs rounded flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(isset($orden) && $orden)
            <div class="mt-6 p-5 bg-surface-container border border-outline-variant rounded space-y-4">
                <div class="flex items-center justify-between border-b border-outline-variant/60 pb-3">
                    <div>
                        <span class="font-mono text-xs text-on-surface-variant uppercase block">ORDEN DE SERVICIO</span>
                        <span class="font-mono text-base font-bold text-primary-container">{{ $orden->codigo_servicio }}</span>
                    </div>
                    <span class="px-3 py-1 border border-primary-container text-primary-container bg-surface-container text-xs font-mono uppercase font-bold">{{ str_replace('_', ' ', $orden->estado) }}</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-mono">
                    <div><span class="text-on-surface-variant">CLIENTE:</span> <span class="text-white font-bold">{{ $orden->cliente_nombre ?? 'N/A' }}</span></div>
                    <div><span class="text-on-surface-variant">EQUIPO:</span> <span class="text-white">{{ $orden->marca ?? '' }} {{ $orden->modelo ?? 'N/A' }}</span></div>
                    <div><span class="text-on-surface-variant">N° SERIE:</span> <span class="text-white">{{ $orden->numero_serie ?? 'N/A' }}</span></div>
                </div>

                <div class="text-xs font-mono bg-[#0d0f12] p-3 rounded border border-[#2d3038]">
                    <span class="text-on-surface-variant block mb-1">FALLA / DETALLES REGISTRADOS:</span>
                    <p class="text-white whitespace-pre-line">{{ $orden->falla_reportada }}</p>
                </div>

                <!-- LÍNEA DE TIEMPO DEL PROCESO -->
                <div class="pt-2">
                    <span class="text-[10px] font-mono text-on-surface-variant uppercase block mb-2">ETAPAS DEL SERVICIO:</span>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-center text-[11px] font-mono">
                        <div class="p-2 rounded border {{ in_array($orden->estado, ['Pendiente', 'Cotizacion_Enviada', 'En_Reparacion', 'Terminado', 'Entregado']) ? 'bg-primary-container/20 border-primary-container text-primary-container font-bold' : 'bg-[#0d0f12] border-outline-variant/40 text-on-surface-variant/40' }}">
                            1. RECEPCIÓN
                        </div>
                        <div class="p-2 rounded border {{ in_array($orden->estado, ['Cotizacion_Enviada', 'En_Reparacion', 'Terminado', 'Entregado']) ? 'bg-primary-container/20 border-primary-container text-primary-container font-bold' : 'bg-[#0d0f12] border-outline-variant/40 text-on-surface-variant/40' }}">
                            2. COTIZACIÓN
                        </div>
                        <div class="p-2 rounded border {{ in_array($orden->estado, ['En_Reparacion', 'Terminado', 'Entregado']) ? 'bg-primary-container/20 border-primary-container text-primary-container font-bold' : 'bg-[#0d0f12] border-outline-variant/40 text-on-surface-variant/40' }}">
                            3. REPARACIÓN
                        </div>
                        <div class="p-2 rounded border {{ in_array($orden->estado, ['Terminado', 'Entregado']) ? 'bg-emerald-500/20 border-emerald-500 text-emerald-400 font-bold' : 'bg-[#0d0f12] border-outline-variant/40 text-on-surface-variant/40' }}">
                            4. TERMINADO
                        </div>
                    </div>
                </div>

                <!-- BLOQUE INTERACTIVO DE COTIZACIÓN PARA EL CLIENTE -->
                @if(in_array($orden->estado, ['Cotizacion_Enviada', 'COTIZADO', 'En_Reparacion', 'Terminado', 'Entregado']))
                    <div class="mt-4 p-4 bg-[#16181f] border border-[#ffb800]/40 rounded space-y-3 font-mono">
                        <div class="flex justify-between items-center border-b border-[#2d3038] pb-2">
                            <h4 class="text-[#ffb800] font-bold uppercase text-xs">Presupuesto y Desglose de Diagnóstico</h4>
                            <span class="text-[10px] text-[#8a8e9b]">Revisión Técnica Completa</span>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-[#8a8e9b]">Costo Mano de Obra:</span>
                                <span class="text-white font-bold">${{ number_format($orden->costo_mano_obra ?? 0, 2) }} MXN</span>
                            </div>

                            @if(isset($refaccionesCotizadas) && count($refaccionesCotizadas) > 0)
                                <div class="pt-2 border-t border-[#2d3038]">
                                    <span class="text-[#8a8e9b] block mb-1">Refacciones Requeridas:</span>
                                    <ul class="space-y-1 pl-2 text-slate-300">
                                        @foreach($refaccionesCotizadas as $ref)
                                            <li class="flex justify-between">
                                                <span>• {{ $ref->nombre }} (x{{ $ref->cantidad }})</span>
                                                <span class="font-bold text-white">${{ number_format($ref->precio_unitario * $ref->cantidad, 2) }} MXN</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @elseif(($orden->costo_refacciones ?? 0) > 0)
                                <div class="flex justify-between">
                                    <span class="text-[#8a8e9b]">Costo Refacciones / Insumos:</span>
                                    <span class="text-white font-bold">${{ number_format($orden->costo_refacciones, 2) }} MXN</span>
                                </div>
                            @endif

                            <div class="pt-2 border-t border-[#2d3038] flex justify-between text-sm">
                                <span class="text-white font-bold">TOTAL ESTIMADO:</span>
                                <span class="text-[#ffb800] font-bold">${{ number_format($orden->costo_total ?? 0, 2) }} MXN</span>
                            </div>
                            <div class="flex justify-between text-emerald-400 font-bold text-xs">
                                <span>ANTICIPO MÍNIMO REQUERIDO (50%):</span>
                                <span>${{ number_format(($orden->costo_total ?? 0) * 0.50, 2) }} MXN</span>
                            </div>
                        </div>

                        <!-- Formulario de Respuesta para el Cliente -->
                        @if(in_array($orden->estado, ['Cotizacion_Enviada', 'COTIZADO']))
                            <form action="{{ route('cotizacion.responder') }}" method="POST" class="space-y-3 pt-3 border-t border-[#2d3038]">
                                @csrf
                                <input type="hidden" name="orden_id" value="{{ $orden->id }}">
                                <textarea name="nota_cliente" placeholder="Escribe un comentario o solicitud de ajuste si lo requieres..." class="w-full bg-[#0d0f12] border border-[#2d3038] text-white p-2 text-xs rounded focus:border-[#ffb800] focus:outline-none"></textarea>
                                
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <button type="submit" name="accion" value="aceptar" class="flex-1 py-2.5 bg-emerald-600 text-white font-bold rounded hover:bg-emerald-500 transition-colors uppercase text-xs">
                                        ✓ Aceptar Cotización
                                    </button>
                                    <button type="submit" name="accion" value="ajustar" class="flex-1 py-2.5 bg-amber-600 text-white font-bold rounded hover:bg-amber-500 transition-colors uppercase text-xs">
                                        💬 Solicitar Ajuste
                                    </button>
                                    <button type="submit" name="accion" value="rechazar" class="flex-1 py-2.5 bg-rose-600 text-white font-bold rounded hover:bg-rose-500 transition-colors uppercase text-xs">
                                        ✕ Rechazar
                                    </button>
                                </div>
                            </form>
                        @elseif($orden->estado === 'En_Reparacion')
                            <div class="p-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded text-xs text-center font-bold">
                                ✓ COTIZACIÓN ACEPTADA - EL TÉCNICO SE ENCUENTRA TRABAJANDO EN TU EQUIPO.
                            </div>
                        @endif

                    </div>
                @endif
            </div>
        @elseif(request('codigo'))
            <div class="mt-6 p-4 bg-surface-container border border-error/50 text-error text-xs font-mono">
                No se encontró ninguna orden de servicio con el código o número de serie provisto [{{ request('codigo') }}]. Verifique el comprobante impreso.
            </div>
        @endif
    </div>

    <!-- SECCIÓN: FASES DEL CICLO DE REPARACIÓN -->
    <div id="taller" class="space-y-6">
        <div class="flex items-center justify-between border-b border-outline-variant pb-3">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-primary-container">precision_manufacturing</span>
                <h2 class="text-headline-lg font-bold text-white uppercase text-xl">Protocolo de Intervención Técnica</h2>
            </div>
            <span class="text-xs font-mono text-on-surface-variant">SOPORTEC v4.2 SPEC</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Fase 01 -->
            <div class="noir-card bg-surface-container-low p-5 border-t-2 border-outline-variant">
                <div class="text-on-surface-variant text-[10px] uppercase font-mono mb-2">FASE 01</div>
                <h3 class="text-white font-bold text-sm uppercase mb-2">Ingreso & Registro</h3>
                <p class="text-on-surface-variant text-xs leading-normal font-body-sm">
                    Recepción formal, inspección óptica de chasis, toma de evidencias fotográficas y generación de ticket térmico.
                </p>
            </div>

            <!-- Fase 02 -->
            <div class="noir-card bg-surface-container-low p-5 border-t-2 border-noir-blue">
                <div class="text-secondary text-[10px] uppercase font-mono mb-2">FASE 02</div>
                <h3 class="text-white font-bold text-sm uppercase mb-2">Diagnóstico ($0)</h3>
                <p class="text-on-surface-variant text-xs leading-normal font-body-sm">
                    Revisión eléctrica, prueba de componentes y análisis de circuitos sin costo ni compromiso alguno.
                </p>
            </div>

            <!-- Fase 03 -->
            <div class="noir-card bg-surface-container-low p-5 border-t-2 border-primary-container">
                <div class="text-primary-container text-[10px] uppercase font-mono mb-2">FASE 03</div>
                <h3 class="text-white font-bold text-sm uppercase mb-2">Cotización</h3>
                <p class="text-on-surface-variant text-xs leading-normal font-body-sm">
                    Presupuesto digital transparente desglosando mano de obra y refacciones con aprobación en línea.
                </p>
            </div>

            <!-- Fase 04 -->
            <div class="noir-card bg-surface-container-low p-5 border-t-2 border-outline-variant">
                <div class="text-on-surface-variant text-[10px] uppercase font-mono mb-2">FASE 04</div>
                <h3 class="text-white font-bold text-sm uppercase mb-2">Reparación & QA</h3>
                <p class="text-on-surface-variant text-xs leading-normal font-body-sm">
                    Sustitución con refacciones originales, soldadura de micro-precisión y pruebas de estrés térmico continuo.
                </p>
            </div>

            <!-- Fase 05 -->
            <div class="noir-card bg-surface-container-low p-5 border-t-2 border-white">
                <div class="text-white text-[10px] uppercase font-mono mb-2">FASE 05</div>
                <h3 class="text-white font-bold text-sm uppercase mb-2">Entrega & Descuento</h3>
                <p class="text-on-surface-variant text-xs leading-normal font-body-sm">
                    Emisión de factura y póliza de garantía de 90 días. Descuento del 15% directo a partir del 6º equipo.
                </p>
            </div>
        </div>
    </div>

    <!-- SECCIÓN: BENEFICIO FIDELIDAD & GARANTÍA -->
    <div id="politicas" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="noir-card bg-surface-container-lowest p-6 border-l-2 border-primary-container">
            <span class="material-symbols-outlined text-primary-container text-2xl mb-2">stars</span>
            <h3 class="text-headline-md font-bold text-white uppercase text-base mb-2">Descuento de Fidelidad: 15% al Superar 5 Equipos</h3>
            <p class="text-on-surface-variant text-xs leading-relaxed mb-4">
                El sistema detecta automáticamente el historial de su cuenta. Una vez que registra y repara más de 5 equipos con nosotros, todas las órdenes subsecuentes reciben un <strong class="text-primary-container">descuento automático del 15%</strong> reflejado en su ticket de entrega.
            </p>
            <div class="inline-block px-3 py-1 bg-surface-container text-primary-container font-mono text-xs border border-outline-variant">
                REGLA: EQUIPOS_REPARADOS_COUNT &gt; 5 &rarr; APLICA_DESCUENTO = TRUE (-15%)
            </div>
        </div>

        <div id="horarios" class="noir-card bg-surface-container-lowest p-6 border-l-2 border-secondary">
            <span class="material-symbols-outlined text-secondary text-2xl mb-2">schedule</span>
            <h3 class="text-headline-md font-bold text-white uppercase text-base mb-2">Horarios & Tiempos de Respuesta</h3>
            <ul class="text-xs font-mono space-y-2 text-on-surface-variant mb-4">
                <li class="flex justify-between border-b border-outline-variant/40 pb-1"><span>Lunes a Viernes:</span> <span class="text-white">08:00 - 19:00 hrs</span></li>
                <li class="flex justify-between border-b border-outline-variant/40 pb-1"><span>Sábados:</span> <span class="text-white">09:00 - 15:00 hrs</span></li>
                <li class="flex justify-between text-primary-container"><span>Tiempo de Diagnóstico:</span> <span>24 a 48 Horas Garantizado</span></li>
            </ul>
            <div id="ubicacion" class="inline-block px-3 py-1 bg-surface-container text-white font-mono text-xs border border-outline-variant">
                UBICACIÓN: LAB CENTRAL - AV. TECNOLÓGICA 1024
            </div>
        </div>
    </div>
</div>
@endsection