@extends('layouts.private-noir')

@section('title', 'TecnoFix Noir — Consola de Diagnóstico Técnico')

@section('content')
    <!-- ========================================== -->
    <!-- TECHNICAL NOIR HEADER & STATUS             -->
    <!-- ========================================== -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#1f2e4d]">
        <div>
            <span class="text-[10px] font-display uppercase tracking-[0.08em] text-[#7a889b] block">
                LABORATORIO CENTRAL • PISO 02 • CLEANROOM ISO-5
            </span>
            <h1 class="text-2xl font-display font-bold text-white tracking-tight mt-0.5 uppercase">
                ESTACIÓN DE DIAGNÓSTICO #04
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <!-- Technical Status Badge: En reparación -->
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#1e180d] border border-[#fca311] text-[#fca311] text-[10px] font-display font-semibold uppercase tracking-[0.08em]">
                <span class="w-1.5 h-1.5 bg-[#fca311]"></span>
                <span>EN REPARACIÓN: #ORD-9821</span>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- STATUS PALETTE SHOWCASE (4 TECHNICAL STATES) -->
    <!-- ========================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 tabular-nums">
        <div class="bg-[#080d18] border border-[#14213d] p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-display uppercase tracking-[0.08em] text-[#7a889b]">EN DIAGNÓSTICO</span>
                <span class="w-1.5 h-1.5 bg-[#7a889b]"></span>
            </div>
            <div class="text-2xl font-display font-bold text-[#e5e5e5]">04</div>
            <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-0.5 bg-[#14213d] border border-[#3a506b] text-[#e5e5e5] text-[10px] font-display uppercase tracking-[0.08em]">
                <span>BAHÍAS 01-04</span>
            </div>
        </div>

        <div class="bg-[#080d18] border border-[#14213d] p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-display uppercase tracking-[0.08em] text-[#7a889b]">EN REPARACIÓN</span>
                <span class="w-1.5 h-1.5 bg-[#fca311]"></span>
            </div>
            <div class="text-2xl font-display font-bold text-[#fca311]">07</div>
            <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-0.5 bg-[#1e180d] border border-[#fca311] text-[#fca311] text-[10px] font-display uppercase tracking-[0.08em]">
                <span>MICRO-SOLDADURA</span>
            </div>
        </div>

        <div class="bg-[#080d18] border border-[#14213d] p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-display uppercase tracking-[0.08em] text-[#7a889b]">ESPERANDO PIEZAS</span>
                <span class="w-1.5 h-1.5 bg-[#ba181b]"></span>
            </div>
            <div class="text-2xl font-display font-bold text-[#ff8fa3]">02</div>
            <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-0.5 bg-[#200b0e] border border-[#ba181b] text-[#ff8fa3] text-[10px] font-display uppercase tracking-[0.08em]">
                <span>IMPORTACIÓN OEM</span>
            </div>
        </div>

        <div class="bg-[#080d18] border border-[#14213d] p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-display uppercase tracking-[0.08em] text-[#7a889b]">LISTO PARA ENTREGA</span>
                <span class="w-1.5 h-1.5 bg-[#2b9348]"></span>
            </div>
            <div class="text-2xl font-display font-bold text-[#70e000]">12</div>
            <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-0.5 bg-[#0c1a11] border border-[#2b9348] text-[#70e000] text-[10px] font-display uppercase tracking-[0.08em]">
                <span>QA APROBADO</span>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TECHNICAL DIAGNOSTICS TABLE (0px SHARP)    -->
    <!-- ========================================== -->
    <div class="bg-[#080d18] border border-[#14213d]">
        <div class="px-5 py-3.5 border-b border-[#14213d] flex items-center justify-between">
            <span class="font-display text-xs font-semibold uppercase tracking-[0.06em] text-[#e5e5e5]">
                COLA DE TELEMETRÍA &amp; DIAGNÓSTICO DE HARDWARE
            </span>
            <span class="font-mono text-xs text-[#7a889b]">ACTUALIZADO: TIEMPO REAL</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs tabular-nums">
                <thead>
                    <tr class="bg-[#080d18] border-b border-[#14213d] font-display text-[11px] uppercase tracking-[0.06em] text-[#7a889b]">
                        <th class="py-3 px-4">ORDEN</th>
                        <th class="py-3 px-4">EQUIPO / CHASIS</th>
                        <th class="py-3 px-4">SERIAL / MAC</th>
                        <th class="py-3 px-4">TELEMETRÍA</th>
                        <th class="py-3 px-4">ESTADO</th>
                    </tr>
                </thead>
                <tbody class="font-mono">
                    <tr class="bg-black border-b border-[#0f1828] hover:bg-[#0e172a] hover:border-l-2 hover:border-l-[#fca311] transition-colors">
                        <td class="py-3.5 px-4 text-white font-semibold">#ORD-9821</td>
                        <td class="py-3.5 px-4 text-[#e5e5e5]">Lenovo ThinkPad P1 Gen 5</td>
                        <td class="py-3.5 px-4 text-[#7a889b]">PF-44X891-2023</td>
                        <td class="py-3.5 px-4 text-[#fca311]">19.2V @ 0.3Ω (BATT+)</td>
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-[#1e180d] border border-[#fca311] text-[#fca311] text-[10px] font-display uppercase tracking-[0.08em]">
                                <span class="w-1.5 h-1.5 bg-[#fca311]"></span>
                                EN REPARACIÓN
                            </span>
                        </td>
                    </tr>
                    <tr class="bg-[#05080f] border-b border-[#0f1828] hover:bg-[#0e172a] hover:border-l-2 hover:border-l-[#fca311] transition-colors">
                        <td class="py-3.5 px-4 text-white font-semibold">#ORD-9820</td>
                        <td class="py-3.5 px-4 text-[#e5e5e5]">Dell PowerEdge R740 Server</td>
                        <td class="py-3.5 px-4 text-[#7a889b]">ST-99841-DELL-MX</td>
                        <td class="py-3.5 px-4 text-[#e5e5e5]">PSU2 RAIL 12V OK</td>
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-[#14213d] border border-[#3a506b] text-[#e5e5e5] text-[10px] font-display uppercase tracking-[0.08em]">
                                <span class="w-1.5 h-1.5 bg-[#7a889b]"></span>
                                EN DIAGNÓSTICO
                            </span>
                        </td>
                    </tr>
                    <tr class="bg-black border-b border-[#0f1828] hover:bg-[#0e172a] hover:border-l-2 hover:border-l-[#fca311] transition-colors">
                        <td class="py-3.5 px-4 text-white font-semibold">#ORD-9818</td>
                        <td class="py-3.5 px-4 text-[#e5e5e5]">MacBook Pro 16" M2 Max</td>
                        <td class="py-3.5 px-4 text-[#7a889b]">C02X9918Q6L4</td>
                        <td class="py-3.5 px-4 text-[#ff8fa3]">CD3217B12 BGA PENDIENTE</td>
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-[#200b0e] border border-[#ba181b] text-[#ff8fa3] text-[10px] font-display uppercase tracking-[0.08em]">
                                <span class="w-1.5 h-1.5 bg-[#ba181b]"></span>
                                ESPERANDO PIEZAS
                            </span>
                        </td>
                    </tr>
                    <tr class="bg-[#05080f] border-b border-[#0f1828] hover:bg-[#0e172a] hover:border-l-2 hover:border-l-[#fca311] transition-colors">
                        <td class="py-3.5 px-4 text-white font-semibold">#ORD-9814</td>
                        <td class="py-3.5 px-4 text-[#e5e5e5]">HPE ProLiant DL380 Gen10</td>
                        <td class="py-3.5 px-4 text-[#7a889b]">MXQ948102Z</td>
                        <td class="py-3.5 px-4 text-[#70e000]">BURN-IN 12H PASS (62°C)</td>
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-[#0c1a11] border border-[#2b9348] text-[#70e000] text-[10px] font-display uppercase tracking-[0.08em]">
                                <span class="w-1.5 h-1.5 bg-[#2b9348]"></span>
                                LISTO PARA ENTREGA
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
