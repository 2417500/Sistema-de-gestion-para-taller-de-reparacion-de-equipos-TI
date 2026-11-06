@extends('layouts.public')

@section('title', 'Taller y Seguimiento de Reparaciones')

@section('content')
<div class="max-w-7xl mx-auto px-4 lg:px-8 py-10">
    <!-- HERO SECTION TÉCNICO NOIR -->
    <div class="noir-card bg-surface-container-lowest p-6 lg:p-12 mb-12 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-container via-noir-blue to-primary-container"></div>
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-surface-container border border-outline-variant font-label-sm text-xs text-primary-container uppercase tracking-widest mb-4">
                <span class="w-1.5 h-1.5 bg-primary-container animate-ping"></span>
                <span>Laboratorio de Diagnóstico e Ingeniería de Hardware</span>
            </div>
            <h1 class="text-headline-xl font-headline-xl font-bold text-white uppercase tracking-tight mb-4">
                Recuperación, Diagnóstico & Ciclo de Vida de Equipos
            </h1>
            <p class="font-body-lg text-on-surface text-base leading-relaxed mb-8">
                Diagnóstico minucioso sin costo. Monitoree el estado de reparación de laptops, estaciones de trabajo y componentes en tiempo real con transparencia absoluta y validación de refacciones originales.
            </p>

            <!-- Buscador Rápido de Ticket en Hero -->
            <form action="{{ url('/seguimiento') }}" method="GET" class="flex flex-col sm:flex-row gap-3 max-w-xl">
                <div class="relative flex-grow">
                    <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant text-lg">qr_code_scanner</span>
                    <input type="text" name="codigo" placeholder="Ej: ST-2026-0001 o Número de Serie..." required
                           class="w-full pl-10 pr-4 py-2.5 bg-surface-container border border-outline-variant font-label-md text-sm text-white placeholder-on-surface-variant/40 focus:border-primary-container focus:ring-0 outline-none uppercase">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-primary-container text-noir-black font-label-md text-xs font-bold uppercase tracking-wider hover:bg-white transition-colors amber-glow flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">search</span>
                    <span>Consultar Estado</span>
                </button>
            </form>
        </div>
    </div>

    <!-- SECCIÓN DE SEGUIMIENTO / PASOS DE REPARACIÓN -->
    <div id="seguimiento" class="mb-16">
        <div class="flex items-center justify-between border-b border-outline-variant pb-3 mb-8">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-primary-container">timeline</span>
                <h2 class="text-headline-lg font-headline-lg font-bold text-white uppercase tracking-wider text-xl">
                    Ciclo de Reparación de Hardware
                </h2>
            </div>
            <span class="text-xs font-mono text-on-surface-variant">PROTOCOLO ESTÁNDAR</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 font-label-sm">
            <!-- Paso 1: Ingreso -->
            <div class="noir-card bg-surface-container-low p-5 border-t-2 border-outline-variant">
                <div class="text-on-surface-variant text-[10px] uppercase font-mono mb-2">FASE 01</div>
                <h3 class="text-white font-bold text-sm uppercase mb-2">Ingreso & Evidencia</h3>
                <p class="text-on-surface-variant text-xs leading-normal font-body-sm">
                    Recepción formal, registro fotográfico de daños físicos externos y generación del ticket con código único.
                </p>
            </div>

            <!-- Paso 2: Diagnóstico -->
            <div class="noir-card bg-surface-container-low p-5 border-t-2 border-noir-blue">
                <div class="text-secondary text-[10px] uppercase font-mono mb-2">FASE 02</div>
                <h3 class="text-white font-bold text-sm uppercase mb-2">Diagnóstico Técnico</h3>
                <p class="text-on-surface-variant text-xs leading-normal font-body-sm">
                    Revisión minuciosa en banco técnico sin costo. Determinación de causas raíz y fallas en circuitos.
                </p>
            </div>

            <!-- Paso 3: Cotización -->
            <div class="noir-card bg-surface-container-low p-5 border-t-2 border-primary-container">
                <div class="text-primary-container text-[10px] uppercase font-mono mb-2">FASE 03</div>
                <h3 class="text-white font-bold text-sm uppercase mb-2">Aprobación Cliente</h3>
                <p class="text-on-surface-variant text-xs leading-normal font-body-sm">
                    Presupuesto desglosado con mano de obra y refacciones. El cliente autoriza en línea con un solo clic.
                </p>
            </div>

            <!-- Paso 4: Reparación & QA -->
            <div class="noir-card bg-surface-container-low p-5 border-t-2 border-outline-variant">
                <div class="text-on-surface-variant text-[10px] uppercase font-mono mb-2">FASE 04</div>
                <h3 class="text-white font-bold text-sm uppercase mb-2">Reparación & QA</h3>
                <p class="text-on-surface-variant text-xs leading-normal font-body-sm">
                    Sustitución de componentes, control térmico, pruebas de estrés y certificación de calidad.
                </p>
            </div>

            <!-- Paso 5: Entrega & Factura -->
            <div class="noir-card bg-surface-container-low p-5 border-t-2 border-white">
                <div class="text-white text-[10px] uppercase font-mono mb-2">FASE 05</div>
                <h3 class="text-white font-bold text-sm uppercase mb-2">Entrega & Descuento</h3>
                <p class="text-on-surface-variant text-xs leading-normal font-body-sm">
                    Emisión de ticket/factura térmica. 15% de descuento automático acumulado a clientes con más de 5 equipos reparados.
                </p>
            </div>
        </div>
    </div>

    <!-- SECCIÓN: POLÍTICAS & COMPROMISO -->
    <div id="politicas" class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
        <div class="noir-card bg-surface-container-lowest p-6 border-l-2 border-primary-container">
            <span class="material-symbols-outlined text-primary-container text-2xl mb-2">verified</span>
            <h3 class="text-headline-md font-bold text-white uppercase mb-2">Programa de Fidelidad de Hardware</h3>
            <p class="text-on-surface-variant text-xs leading-relaxed mb-4 font-body-md">
                Premiamos la confianza de nuestros clientes corporativos y particulares. Nuestro sistema detecta automáticamente cuando su cuenta supera los 5 equipos reparados con éxito, aplicando un <strong class="text-primary-container">15% de descuento permanente</strong> en mano de obra y refacciones.
            </p>
            <div class="inline-block px-3 py-1 bg-surface-container text-white font-mono text-xs border border-outline-variant">
                REGLA: EQUIPOS_REPARADOS_COUNT &gt; 5 &rarr; APLICA_DESCUENTO = TRUE
            </div>
        </div>

        <div class="noir-card bg-surface-container-lowest p-6 border-l-2 border-secondary">
            <span class="material-symbols-outlined text-secondary text-2xl mb-2">workspace_premium</span>
            <h3 class="text-headline-md font-bold text-white uppercase mb-2">Garantía por Escrito de 90 Días</h3>
            <p class="text-on-surface-variant text-xs leading-relaxed mb-4 font-body-md">
                Cada intervención técnica queda registrada en nuestra bitácora inmutable de auditoría con los números de serie de las piezas reemplazadas y la firma del técnico responsable.
            </p>
            <div class="inline-block px-3 py-1 bg-surface-container text-white font-mono text-xs border border-outline-variant">
                CERTIFICACIÓN DE BANCO TÉCNICO NOIR // ALACENADO CON SKU
            </div>
        </div>
    </div>
</div>
@endsection

