@extends('layouts.private')

@section('title', 'TecnoFix Solutions — Consola Operativa Multi-Rol')

@section('content')
    @php
        $activeRole = request('rol', 'tecnico');
    @endphp

    <!-- ========================================== -->
    <!-- 1. CONTEXTUAL GREETING & CALM STATUS       -->
    <!-- ========================================== -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-brand-border/60">
        <div>
            <div class="flex items-center gap-2 text-xs font-medium text-brand-muted">
                <span>Taller Central</span>
                <span>•</span>
                <span class="text-slate-700">Piso 2, Banco de Microelectrónica #04</span>
            </div>
            <h1 class="text-xl font-bold font-display text-slate-900 tracking-tight mt-0.5">
                Buenos días, {{ auth()->check() ? auth()->user()->name : 'Carlos' }}
            </h1>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200 tabular-nums">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                Diagnóstico Activo: #ORD-9821
            </span>
            <a href="{{ route('dashboard.noir') }}" class="px-2.5 py-1 bg-slate-900 hover:bg-black text-amber-400 text-xs font-mono font-medium rounded-btn transition-colors shadow-subtle">
                Modo Noir
            </a>
            <button type="button" class="px-2.5 py-1 bg-white hover:bg-slate-50 text-slate-700 text-xs font-medium border border-brand-border rounded-btn transition-colors shadow-subtle">
                Pausar Banco
            </button>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 2. EXACTLY 3 CLEAN KEY METRICS (KPIS)      -->
    <!-- ========================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 tabular-nums">
        <!-- Metric 1 -->
        <div class="bg-white border border-brand-border rounded-card p-5 shadow-subtle">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-brand-muted uppercase tracking-wider">Equipos en Diagnóstico</span>
                <span class="material-symbols-outlined text-slate-400 text-[18px]">biotech</span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold font-display text-slate-900">4</span>
                <span class="text-xs text-brand-muted font-normal">en laboratorio</span>
            </div>
            <p class="mt-1 text-[11px] text-slate-500 flex items-center gap-1">
                <span class="text-emerald-600 font-medium">Capacidad 75%</span> — flujo regular
            </p>
        </div>

        <!-- Metric 2 -->
        <div class="bg-white border border-brand-border rounded-card p-5 shadow-subtle">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-brand-muted uppercase tracking-wider">Órdenes Prioritarias</span>
                <span class="material-symbols-outlined text-amber-500 text-[18px]">priority_high</span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold font-display text-slate-900">2</span>
                <span class="text-xs text-amber-700 font-medium bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">SLA 24h</span>
            </div>
            <p class="mt-1 text-[11px] text-slate-500">
                Requieren pruebas de estrés antes de las 16:00
            </p>
        </div>

        <!-- Metric 3 -->
        <div class="bg-white border border-brand-border rounded-card p-5 shadow-subtle">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-brand-muted uppercase tracking-wider">Tiempo Prom. Resolución</span>
                <span class="material-symbols-outlined text-slate-400 text-[18px]">timer</span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold font-display text-slate-900">3.8h</span>
                <span class="text-xs text-emerald-600 font-medium">▼ 18m</span>
            </div>
            <p class="mt-1 text-[11px] text-slate-500">
                Promedio semanal con instrumental JBC
            </p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- VIEW: TÉCNICO (DEFAULT ACTIVE WORKSPACE)   -->
    <!-- ========================================== -->
    <section id="view-tecnico" class="role-section space-y-6 {{ $activeRole === 'tecnico' ? '' : 'hidden' }}">
        <div class="bg-white border border-brand-border rounded-card shadow-subtle overflow-hidden">
            <!-- Card Header with Device Identity -->
            <div class="p-6 border-b border-brand-border flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 tabular-nums">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-700">ORD-9821</span>
                        <span class="text-xs text-brand-muted">|</span>
                        <span class="text-xs text-brand-muted">Asset: <span class="font-mono text-slate-700 font-medium">TX-88301</span></span>
                    </div>
                    <h2 class="text-lg font-bold font-display text-slate-900">
                        Lenovo ThinkPad P1 Gen 5
                    </h2>
                    <p class="text-xs text-brand-muted font-mono">
                        S/N: PF-44X891-2023 • Intel Core i9-12900H • RTX A4500
                    </p>
                </div>
                <!-- Unit Status Badge & Action -->
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-btn text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Micro-soldadura VRM
                    </span>
                    <button type="button" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-medium rounded-btn border border-brand-border transition-colors">
                        Ver Ficha Técnica
                    </button>
                </div>
            </div>

            <!-- Body: Two-Column Focused Workbench -->
            <div class="p-6 grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left: Electrical Fault Scope & Component -->
                <div class="lg:col-span-5 space-y-5">
                    <div class="space-y-2">
                        <span class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Diagnóstico Confirmado</span>
                        <div class="p-4 rounded-card bg-slate-50 border border-brand-border space-y-2.5">
                            <div class="flex items-center justify-between text-xs font-medium">
                                <span class="text-amber-800 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px] text-amber-600">error_outline</span>
                                    Corto a tierra en riel BATT+ (19.5V)
                                </span>
                                <span class="font-mono text-slate-500 tabular-nums">19.2V @ 4.8A</span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Mosfet de canal N (PQ301) en corto directo, impidiendo encendido primario. Circuito de protección disparado.
                            </p>
                            <div class="pt-2 border-t border-brand-border/70 grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <span class="text-slate-400 block text-[10px]">IMPEDANCIA BATT+</span>
                                    <span class="font-semibold text-rose-600 font-mono tabular-nums">0.3 Ω (CORTO)</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px]">ESTADO TÉRMICO</span>
                                    <span class="font-semibold text-slate-800 font-mono">Normalizado</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Required Replacement Stock Mini -->
                    <div class="space-y-2">
                        <span class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Componente Asignado</span>
                        <div class="flex items-center justify-between p-3 rounded-card border border-brand-border bg-white shadow-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-btn bg-slate-100 flex items-center justify-center text-slate-600">
                                    <span class="material-symbols-outlined text-[18px]">memory</span>
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-slate-900 block">Mosfet Canal N AON6512 30V</span>
                                    <span class="text-[11px] text-brand-muted tabular-nums">Gaveta ALM-G14 • 8 disponibles</span>
                                </div>
                            </div>
                            <span class="text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                Suministrado
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right: Human-Focused 3-Step Timeline -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Flujo de Reparación (3 Pasos)</span>
                        <span class="text-xs text-slate-500">Paso 2 de 3 en curso</span>
                    </div>
                    <div class="space-y-3">
                        <!-- Step 1: Completed -->
                        <div class="p-3.5 rounded-card border border-brand-border bg-slate-50/60 flex items-start gap-3.5">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-semibold shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[15px]">check</span>
                            </div>
                            <div class="flex-1 space-y-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xs font-semibold text-slate-800">1. Inspección Microscópica &amp; Desoldado</h3>
                                    <span class="text-[11px] font-mono text-slate-400 tabular-nums">09:15</span>
                                </div>
                                <p class="text-xs text-slate-600 leading-normal">
                                    Mosfet dañado retirado con tobera a 360°C. Pads limpiados y libres de puentes de estaño.
                                </p>
                            </div>
                        </div>

                        <!-- Step 2: Active -->
                        <div class="p-3.5 rounded-card border-2 border-brand-navy/30 bg-white flex items-start gap-3.5 shadow-subtle">
                            <div class="w-6 h-6 rounded-full bg-brand-navy text-white flex items-center justify-center text-xs font-semibold shrink-0 mt-0.5">
                                2
                            </div>
                            <div class="flex-1 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xs font-semibold text-slate-900">2. Montaje de Componente &amp; Test Térmico</h3>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 tabular-nums">
                                        En curso (2h 45m)
                                    </span>
                                </div>
                                <p class="text-xs text-slate-600 leading-normal">
                                    Instalación de Mosfet AON6512 nuevo. Prueba de carga y ciclo de estrés térmico con AIDA64 estable a 68°C.
                                </p>
                                <div class="pt-1 flex items-center gap-2">
                                    <button type="button" class="px-2.5 py-1 bg-brand-navy text-white text-xs font-medium rounded-btn hover:bg-slate-800 transition-colors">
                                        Completar Paso
                                    </button>
                                    <button type="button" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-btn transition-colors">
                                        Añadir Nota a Bitácora
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Upcoming -->
                        <div class="p-3.5 rounded-card border border-brand-border bg-white flex items-start gap-3.5 opacity-60">
                            <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 border border-brand-border flex items-center justify-center text-xs font-semibold shrink-0 mt-0.5">
                                3
                            </div>
                            <div class="flex-1 space-y-0.5">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xs font-medium text-slate-700">3. Control de Calidad Final &amp; Cierre</h3>
                                    <span class="text-[11px] text-slate-400 tabular-nums">Estimado 15:30</span>
                                </div>
                                <p class="text-xs text-slate-500">
                                    Reensamble completo con pasta Kryonaut, chequeo de puertos y pase a Recepción.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- VIEW: RECEPCIÓN                            -->
    <!-- ========================================== -->
    <section id="view-recepcion" class="role-section space-y-6 {{ $activeRole === 'recepcion' ? '' : 'hidden' }}">
        <div class="bg-white border border-brand-border rounded-card p-6 shadow-subtle space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-brand-border">
                <div>
                    <span class="text-xs font-medium text-brand-muted uppercase tracking-wider">Módulo de Entrada</span>
                    <h2 class="text-lg font-bold font-display text-slate-900">Admisión Rápida de Hardware</h2>
                </div>
                <button type="button" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 border border-brand-border text-slate-700 text-xs font-medium rounded-btn flex items-center gap-1.5 shadow-subtle">
                    <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span>
                    <span>Escanear Orden Anterior</span>
                </button>
            </div>
            <form class="grid grid-cols-1 md:grid-cols-2 gap-4" onsubmit="event.preventDefault(); alert('Equipo ingresado exitosamente en sistema.');">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Cliente / Empresa</label>
                    <input type="text" value="Soluciones Logísticas Bajío S.A."
                        class="w-full px-3 py-2 bg-slate-50 border border-brand-border rounded-btn text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-brand-navy" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Contacto / Correo</label>
                    <input type="email" value="contacto@logbajio.mx"
                        class="w-full px-3 py-2 bg-slate-50 border border-brand-border rounded-btn text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-brand-navy" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Modelo de Equipo</label>
                    <input type="text" value="Dell PowerEdge R740 Server"
                        class="w-full px-3 py-2 bg-slate-50 border border-brand-border rounded-btn text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-brand-navy" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Número de Serie (Service Tag)</label>
                    <input type="text" value="ST-99841-DELL-MX"
                        class="w-full px-3 py-2 bg-slate-50 border border-brand-border rounded-btn text-xs font-mono text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-brand-navy" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-slate-700 mb-1">Falla Reportada</label>
                    <textarea rows="2" class="w-full px-3 py-2 bg-slate-50 border border-brand-border rounded-btn text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-brand-navy">Servidor no completa POST. Led ámbar en PSU 2 redundante con error iDRAC de fatal bus.</textarea>
                </div>
                <div class="md:col-span-2 pt-2 flex items-center justify-between border-t border-brand-border">
                    <span class="text-xs text-brand-muted">Banco recomendado: #02 (Enterprise Servidores)</span>
                    <button type="submit" class="px-4 py-2 bg-brand-navy hover:bg-slate-800 text-white text-xs font-semibold rounded-btn shadow-subtle transition-colors">
                        Generar Orden &amp; Imprimir Etiqueta
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- VIEW: ADMINISTRADOR                        -->
    <!-- ========================================== -->
    <section id="view-admin" class="role-section space-y-6 {{ $activeRole === 'admin' ? '' : 'hidden' }}">
        <div class="bg-white border border-brand-border rounded-card p-6 shadow-subtle space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-brand-border">
                <div>
                    <span class="text-xs font-medium text-brand-muted uppercase tracking-wider">Supervisión de Taller</span>
                    <h2 class="text-lg font-bold font-display text-slate-900">Estado de Bancos &amp; SLAs</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-brand-muted">Periodo: Actual</span>
                    <button type="button" class="px-3 py-1 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-medium border border-brand-border rounded-btn">
                        Exportar Informe
                    </button>
                </div>
            </div>
            <div class="divide-y divide-brand-border tabular-nums">
                <div class="py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-btn bg-slate-100 flex items-center justify-center font-bold text-xs text-slate-700">B01</div>
                        <div>
                            <span class="text-xs font-semibold text-slate-900 block">Banco 01 — Ing. Carlos Mendoza</span>
                            <span class="text-[11px] text-brand-muted">Microelectrónica &amp; Circuitos VRM</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-medium text-slate-700">4 órdenes activas</span>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">En SLA</span>
                    </div>
                </div>
                <div class="py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-btn bg-slate-100 flex items-center justify-center font-bold text-xs text-slate-700">B02</div>
                        <div>
                            <span class="text-xs font-semibold text-slate-900 block">Banco 02 — Valeria Sánchez</span>
                            <span class="text-[11px] text-brand-muted">Servidores Enterprise &amp; Racks</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-medium text-slate-700">3 órdenes activas</span>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">En SLA</span>
                    </div>
                </div>
                <div class="py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-btn bg-slate-100 flex items-center justify-center font-bold text-xs text-slate-700">B03</div>
                        <div>
                            <span class="text-xs font-semibold text-slate-900 block">Banco 03 — Roberto Garza</span>
                            <span class="text-[11px] text-brand-muted">Reballing BGA &amp; Estructuras</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-medium text-slate-700">5 órdenes activas</span>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200">Atención</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- VIEW: ALMACÉN                              -->
    <!-- ========================================== -->
    <section id="view-almacen" class="role-section space-y-6 {{ $activeRole === 'almacen' ? '' : 'hidden' }}">
        <div class="bg-white border border-brand-border rounded-card p-6 shadow-subtle space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-brand-border">
                <div>
                    <span class="text-xs font-medium text-brand-muted uppercase tracking-wider">Inventario Rápido</span>
                    <h2 class="text-lg font-bold font-display text-slate-900">Refacciones Disponibles</h2>
                </div>
                <button type="button" class="px-3 py-1.5 bg-brand-navy hover:bg-slate-800 text-white text-xs font-medium rounded-btn shadow-subtle">
                    + Registrar Ingreso
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs tabular-nums">
                    <thead>
                        <tr class="border-b border-brand-border text-brand-muted font-medium uppercase text-[10px]">
                            <th class="py-2.5 px-3">Componente / Número de Parte</th>
                            <th class="py-2.5 px-3">Gaveta</th>
                            <th class="py-2.5 px-3 text-right">Existencia</th>
                            <th class="py-2.5 px-3 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-border text-slate-800">
                        <tr>
                            <td class="py-3 px-3 font-semibold">Mosfet Canal N AON6512 30V DFN</td>
                            <td class="py-3 px-3 font-mono text-slate-500">ALM-G14</td>
                            <td class="py-3 px-3 text-right font-medium text-amber-700">8 unidades</td>
                            <td class="py-3 px-3 text-right">
                                <button type="button" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 rounded text-slate-700 text-xs">Asignar</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 px-3 font-semibold">Controlador PWM ISL95855 CHRTZ</td>
                            <td class="py-3 px-3 font-mono text-slate-500">EST-A3-G08</td>
                            <td class="py-3 px-3 text-right font-medium">14 unidades</td>
                            <td class="py-3 px-3 text-right">
                                <button type="button" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 rounded text-slate-700 text-xs">Asignar</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 px-3 font-semibold">Pasta Térmica Kryonaut Extreme 2g</td>
                            <td class="py-3 px-3 font-mono text-slate-500">ALM-CNS-01</td>
                            <td class="py-3 px-3 text-right font-medium">25 jeringas</td>
                            <td class="py-3 px-3 text-right">
                                <button type="button" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 rounded text-slate-700 text-xs">Asignar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- VIEW: PORTAL CLIENTE                       -->
    <!-- ========================================== -->
    <section id="view-cliente" class="role-section space-y-6 {{ $activeRole === 'cliente' ? '' : 'hidden' }}">
        <div class="bg-white border border-brand-border rounded-card p-6 shadow-subtle space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-brand-border">
                <div>
                    <span class="text-xs font-medium text-brand-muted uppercase tracking-wider">Portal Cliente</span>
                    <h2 class="text-lg font-bold font-display text-slate-900">Seguimiento de Orden #ORD-9821</h2>
                </div>
                <span class="text-xs font-mono text-slate-500 tabular-nums">PIN de consulta: ***891</span>
            </div>
            <div class="p-4 bg-slate-50 rounded-card border border-brand-border space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-800">Lenovo ThinkPad P1 Gen 5</span>
                    <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">En Reparación</span>
                </div>
                <p class="text-xs text-slate-600">
                    Presupuesto autorizado por $3,450.00 MXN para sustitución de módulo de poder VRM con 90 días de garantía.
                </p>
            </div>
            <div class="flex items-center justify-between pt-2">
                <span class="text-xs text-brand-muted tabular-nums">Tiempo estimado de entrega: Hoy, 16:30 hrs</span>
                <button type="button" class="px-3 py-1.5 bg-brand-navy hover:bg-slate-800 text-white text-xs font-medium rounded-btn shadow-subtle">
                    Descargar Comprobante PDF
                </button>
            </div>
        </div>
    </section>
@endsection
