@extends('layouts.private')

@section('title', 'Dashboard Técnico')
@section('page-title', 'Tablero de Control de Reparaciones')
@section('breadcrumb', 'Mesa de Diagnóstico & Telemetría')

@section('header-actions')
    <a href="{{ url('/ordenes/crear') }}" class="px-4 py-2 bg-primary-container text-noir-black font-label-md text-xs font-bold uppercase tracking-wider hover:bg-white transition-colors amber-glow flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">add</span>
        <span>Nuevo Ingreso</span>
    </a>
@endsection

@section('content')
<!-- TARJETAS DE TELEMETRÍA / MÉTRICAS CLAVE -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Métrica 1: Pendientes -->
    <div class="noir-card bg-surface-container-lowest p-5 border-t-2 border-outline">
        <div class="flex items-center justify-between text-on-surface-variant font-label-sm text-xs mb-2">
            <span>COLA EN RECEPCIÓN</span>
            <span class="material-symbols-outlined text-base">inbox</span>
        </div>
        <div class="text-headline-xl font-headline-xl font-bold text-white mb-1">
            {{ $pendientesCount ?? 4 }}
        </div>
        <span class="text-[11px] font-mono text-on-surface-variant">Equipos pendientes de diagnóstico</span>
    </div>

    <!-- Métrica 2: En Proceso -->
    <div class="noir-card bg-surface-container-lowest p-5 border-t-2 border-primary-container">
        <div class="flex items-center justify-between text-primary-container font-label-sm text-xs mb-2">
            <span>EN BANCO TÉCNICO</span>
            <span class="material-symbols-outlined text-base">precision_manufacturing</span>
        </div>
        <div class="text-headline-xl font-headline-xl font-bold text-primary-container mb-1">
            {{ $enProcesoCount ?? 7 }}
        </div>
        <span class="text-[11px] font-mono text-on-surface-variant">Pruebas y reemplazo activo</span>
    </div>

    <!-- Métrica 3: Terminado / Listo para Entrega -->
    <div class="noir-card bg-surface-container-lowest p-5 border-t-2 border-white">
        <div class="flex items-center justify-between text-white font-label-sm text-xs mb-2">
            <span>LISTO PARA ENTREGA</span>
            <span class="material-symbols-outlined text-base">task_alt</span>
        </div>
        <div class="text-headline-xl font-headline-xl font-bold text-white mb-1">
            {{ $terminadosCount ?? 3 }}
        </div>
        <span class="text-[11px] font-mono text-on-surface-variant">Control de calidad QA superado</span>
    </div>

    <!-- Métrica 4: Aprobaciones Pendientes Admin -->
    <div class="noir-card bg-surface-container-lowest p-5 border-t-2 border-noir-blue">
        <div class="flex items-center justify-between text-secondary font-label-sm text-xs mb-2">
            <span>APROBACIÓN ADMIN</span>
            <span class="material-symbols-outlined text-base">shield</span>
        </div>
        <div class="text-headline-xl font-headline-xl font-bold text-secondary mb-1">
            {{ $aprobacionesPendientesCount ?? 1 }}
        </div>
        <span class="text-[11px] font-mono text-on-surface-variant">Exceso refacciones / herramientas</span>
    </div>
</div>

<!-- COLA DE ÓRDENES ACTIVAS (TABLA CON ESTILO NOIR TÉCNICO) -->
<div class="noir-card bg-surface-container-lowest overflow-hidden">
    <div class="p-4 border-b border-outline-variant flex items-center justify-between bg-surface-container-low">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-primary-container text-lg">list_alt</span>
            <h3 class="text-headline-md font-bold text-white uppercase text-sm tracking-wider">
                Cola Activa de Órdenes de Servicio
            </h3>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-mono text-on-surface-variant">ORDENAR POR PRIORIDAD</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left font-label-sm text-xs">
            <thead>
                <tr class="bg-noir-blue text-white uppercase border-b border-outline-variant">
                    <th class="py-3 px-4 font-semibold tracking-wider">Código Servicio</th>
                    <th class="py-3 px-4 font-semibold tracking-wider">Equipo / Modelo</th>
                    <th class="py-3 px-4 font-semibold tracking-wider">Nº Serie</th>
                    <th class="py-3 px-4 font-semibold tracking-wider">Cliente</th>
                    <th class="py-3 px-4 font-semibold tracking-wider">Técnico</th>
                    <th class="py-3 px-4 font-semibold tracking-wider">Estado</th>
                    <th class="py-3 px-4 font-semibold tracking-wider text-right">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/40">
                <!-- Fila de Ejemplo 1 -->
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="py-3 px-4 font-mono font-bold text-primary-container">ST-2026-0042</td>
                    <td class="py-3 px-4 text-white">ThinkPad T14 Gen 3</td>
                    <td class="py-3 px-4 font-mono text-on-surface-variant">PF24XA9918</td>
                    <td class="py-3 px-4 text-on-surface">TechCorp S.A.</td>
                    <td class="py-3 px-4 text-on-surface">Ing. Carlos R.</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-0.5 border border-primary-container text-primary-container bg-surface-container text-[10px] uppercase font-mono font-bold">
                            En_Proceso
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <a href="#" class="px-3 py-1 bg-surface-container border border-outline-variant text-white hover:border-primary-container hover:text-primary-container uppercase transition-colors">
                            Ver Banco
                        </a>
                    </td>
                </tr>

                <!-- Fila de Ejemplo 2 -->
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="py-3 px-4 font-mono font-bold text-primary-container">ST-2026-0041</td>
                    <td class="py-3 px-4 text-white">MacBook Pro 16" M2</td>
                    <td class="py-3 px-4 font-mono text-on-surface-variant">C02G901XMD6R</td>
                    <td class="py-3 px-4 text-on-surface">Mariana Gómez (VIP)</td>
                    <td class="py-3 px-4 text-on-surface-variant font-mono">SIN ASIGNAR</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-0.5 border border-outline text-on-surface bg-transparent text-[10px] uppercase font-mono">
                            Pendiente
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <a href="#" class="px-3 py-1 bg-surface-container border border-outline-variant text-white hover:border-primary-container hover:text-primary-container uppercase transition-colors">
                            Asignar
                        </a>
                    </td>
                </tr>

                <!-- Fila de Ejemplo 3 -->
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="py-3 px-4 font-mono font-bold text-primary-container">ST-2026-0040</td>
                    <td class="py-3 px-4 text-white">Dell Latitude 5420</td>
                    <td class="py-3 px-4 font-mono text-on-surface-variant">8HJ82Z3</td>
                    <td class="py-3 px-4 text-on-surface">Roberto Salas</td>
                    <td class="py-3 px-4 text-on-surface">Ing. Luis V.</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-0.5 border border-white text-noir-black bg-white text-[10px] uppercase font-mono font-bold">
                            Terminado
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <a href="#" class="px-3 py-1 bg-emerald-900 border border-emerald-500/50 text-emerald-200 hover:bg-emerald-800 uppercase transition-colors">
                            Facturar
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

