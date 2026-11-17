<x-app-layout>
    <div class="flex min-h-screen">
        <!-- BARRA LATERAL PORTAL CLIENTE -->
        <aside class="admin-sidebar w-64 border-r min-h-screen flex flex-col justify-between p-4 flex-shrink-0">
            <div class="space-y-6">
                <!-- LOGO / ENCABEZADO SISTEMA -->
                <div class="flex items-center gap-3 pb-4 border-b border-[#588157]">
                    <div class="w-10 h-10 bg-[#588157] text-white font-bold flex items-center justify-center rounded text-sm shadow">
                        SF
                    </div>
                    <div>
                        <h1 class="text-sm font-bold uppercase leading-none font-titulos dynamic-title">TECNOFIX</h1>
                        <span class="text-[10px] font-subtitulos uppercase dynamic-muted">SOPORTEC V4.2</span>
                    </div>
                </div>

                <!-- ETIQUETA DE ROL -->
                <div class="px-2 py-1.5 bg-[#18261f] text-[#a3b18a] text-[10px] font-mono border border-[#588157] rounded text-center font-bold uppercase">
                    PORTAL CLIENTE // SEGUIMIENTO
                </div>

                <!-- NAVEGACIÓN CLIENTE -->
                <nav class="space-y-1.5 font-subtitulos text-xs">
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors bg-[#588157] text-white font-bold">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z"/></svg>
                            <span>DASHBOARD</span>
                        </div>
                    </a>

                    <a href="{{ route('cliente.cotizaciones') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors dynamic-title hover:bg-[#588157]/40">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                            <span>COTIZACIONES PENDIENTES</span>
                        </div>
                    </a>

                    <a href="{{ route('cliente.historial') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors dynamic-title hover:bg-[#588157]/40">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
                            <span>HISTORIAL PEDIDOS</span>
                        </div>
                    </a>

                    <a href="{{ route('cliente.perfil') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors dynamic-title hover:bg-[#588157]/40">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            <span>MI PERFIL</span>
                        </div>
                    </a>
                </nav>
            </div>

            <!-- CONTROLES INFERIORES SIDENAV -->
            <div class="pt-4 border-t border-[#588157] space-y-3">
                <div class="p-3 admin-box rounded space-y-1">
                    <span class="text-[10px] dynamic-muted uppercase block">SESIÓN ACTIVA</span>
                    <span class="text-xs dynamic-title font-bold block truncate">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] text-emerald-400 font-bold uppercase flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> CLIENTE AUTORIZADO
                    </span>
                </div>

                <button type="button" onclick="toggleTheme()" class="w-full flex items-center gap-2.5 px-3 py-2 rounded text-xs font-subtitulos dynamic-title hover:bg-[#588157]/30 transition-colors">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2c5.52 0 10 4.48 10 10s-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2zm0 18c4.42 0 8-3.58 8-8s-3.58-8-8-8v16z"/></svg>
                    <span>CAMBIAR MODO</span>
                </button>
            </div>
        </aside>

        <!-- ÁREA PRINCIPAL -->
        <div class="flex-grow flex flex-col min-h-screen overflow-x-hidden">
            <!-- HEADER SUPERIOR -->
            <header class="admin-sidebar border-b px-6 py-3 flex items-center justify-between gap-4">
                <form action="{{ url('/seguimiento') }}" method="GET" class="flex items-center gap-2 flex-grow max-w-lg">
                    <div class="relative w-full">
                        <svg class="w-4 h-4 fill-current absolute left-3 top-2.5 opacity-50 dynamic-title" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        <input type="text" name="codigo" placeholder="BUSCAR POR SERIAL, IMEI O CÓDIGO (ST-2026-XXXX)..." class="w-full pl-9 pr-3 py-1.5 rounded text-xs outline-none uppercase font-mono">
                    </div>
                    <button type="submit" class="px-3 py-1.5 bg-[#588157] text-white font-bold text-xs rounded hover:bg-[#a3b18a] hover:text-[#1d2e26] transition-colors">ENTER</button>
                </form>

                <div class="flex items-center gap-4">
                    <div class="text-right font-mono text-xs">
                        <div class="font-bold uppercase dynamic-title">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] dynamic-muted">{{ Auth::user()->email }}</div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-rose-400 hover:text-rose-600 transition-colors" title="Cerrar Sesión">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M13 3h-2v10h2V3zm4.83 2.17l-1.42 1.42C17.99 7.86 19 9.81 19 12c0 3.87-3.13 7-7 7s-7-3.13-7-7c0-2.19 1.01-4.14 2.58-5.42L6.17 5.17C4.23 6.82 3 9.26 3 12c0 4.97 4.03 9 9 9s9-4.03 9-9c0-2.74-1.23-5.18-3.17-6.83z"/></svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- CONTENIDO PRINCIPAL: RASTREADOR DE ESTADO PARA TODOS LOS DISPOSITIVOS -->
            <main class="flex-grow p-6 space-y-6 text-xs overflow-y-auto">
                <div class="admin-card p-6 rounded border-l-4 border-[#588157] flex flex-wrap justify-between items-center gap-4">
                    <div>
                        <h1 class="dynamic-title text-lg font-bold uppercase tracking-wider font-titulos">Estado Actual de sus Dispositivos // {{ Auth::user()->name }}</h1>
                        <p class="dynamic-muted text-xs mt-1">Seguimiento en tiempo real de todas las órdenes activas en taller de reparación.</p>
                    </div>
                    @if(isset($ordenes) && $ordenes->count() > 0)
                        <div class="px-4 py-2 admin-box rounded font-bold font-mono">
                            TOTAL DISPOSITIVOS: {{ $ordenes->count() }}
                        </div>
                    @endif
                </div>

                @forelse($ordenes ?? [] as $orden)
                    @php
                        $estado = trim($orden->estado ?? 'Pendiente');
                        $esCancelado = ($estado === 'Cancelado');

                        // PASO 1: RECEPCIÓN (Siempre LISTO una vez creada la orden, salvo cancelada)
                        $paso1_ok = in_array($estado, ['Pendiente', 'Diagnosticando', 'Cotizado', 'En_Proceso', 'Terminado', 'Entregado']);

                        // PASO 2: TÉCNICO
                        $paso2_ok = in_array($estado, ['Terminado', 'Entregado']);
                        $paso2_cotizado = ($estado === 'Cotizado');
                        $paso2_pend = in_array($estado, ['Diagnosticando', 'En_Proceso', 'Pendiente']);

                        // PASO 3: RECEPCIÓN & CAJA
                        $paso3_ok = ($estado === 'Entregado');
                        $paso3_pend = ($estado === 'Terminado');
                    @endphp

                    <div class="admin-card p-6 rounded space-y-6">
                        <div class="flex flex-wrap justify-between items-center border-b border-[#588157]/50 pb-3 gap-2">
                            <div>
                                <span class="dynamic-muted block text-[10px] font-subtitulos">CÓDIGO DE ORDEN ACTIVA</span>
                                <div class="flex items-center gap-3">
                                    <span class="dynamic-title text-base font-bold font-mono">{{ $orden->codigo_servicio }}</span>
                                    @if($esCancelado)
                                        <span class="px-2.5 py-0.5 bg-rose-950/80 border border-rose-500 text-rose-400 rounded text-[10px] font-bold uppercase">ORDEN CANCELADA</span>
                                    @elseif($estado === 'Cotizado')
                                        <a href="{{ route('cliente.cotizaciones') }}" class="px-2.5 py-0.5 bg-amber-500/20 border border-amber-400 text-amber-300 hover:bg-amber-500 hover:text-black rounded text-[10px] font-bold uppercase transition-colors">
                                            COTIZACIÓN LISTA → REVISAR AHORA
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="dynamic-muted block text-[10px] font-subtitulos">EQUIPO REGISTRADO</span>
                                <span class="dynamic-title font-bold uppercase">{{ $orden->marca }} {{ $orden->modelo }} ({{ $orden->numero_serie }})</span>
                            </div>
                        </div>

                        <!-- LÍNEA DE TIEMPO DEL PROCESO OPERATIVO -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                            <!-- PASO 1: RECEPCIÓN -->
                            <div class="relative p-4 rounded border {{ $paso1_ok ? 'border-emerald-500 bg-emerald-950/25 text-emerald-400' : ($esCancelado ? 'border-rose-500/60 bg-rose-950/20 text-rose-400' : 'admin-box opacity-60') }}">
                                <span class="absolute top-3 right-3 text-[10px] font-bold uppercase tracking-wider {{ $paso1_ok ? 'text-emerald-400' : ($esCancelado ? 'text-rose-400' : 'dynamic-muted') }}">
                                    {{ $paso1_ok ? 'LISTO' : ($esCancelado ? 'CANCELADO' : 'AUN NO REALIZADO') }}
                                </span>
                                <div class="mb-2">
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M20 4H4v2h16V4zm1 10v-2l-1-5H4l-1 5v2h1v6h10v-6h4v6h2v-6h1zm-9 4H6v-4h6v4z"/></svg>
                                </div>
                                <span class="font-bold block text-sm font-subtitulos">1. RECEPCIÓN</span>
                                <span class="text-[11px] block mt-1 opacity-90">Registro formal de la orden e ingreso al taller.</span>
                            </div>

                            <!-- PASO 2: TÉCNICO -->
                            <div class="relative p-4 rounded border {{ $paso2_ok ? 'border-emerald-500 bg-emerald-950/25 text-emerald-400' : ($paso2_cotizado ? 'border-amber-400 bg-amber-950/25 text-amber-300' : ($paso2_pend ? 'border-[#a3b18a] bg-[#588157]/25 dynamic-title' : ($esCancelado ? 'border-rose-500/60 bg-rose-950/20 text-rose-400' : 'admin-box opacity-60'))) }}">
                                <span class="absolute top-3 right-3 text-[10px] font-bold uppercase tracking-wider {{ $paso2_ok ? 'text-emerald-400' : ($paso2_cotizado ? 'text-amber-300' : ($paso2_pend ? 'text-[#a3b18a]' : ($esCancelado ? 'text-rose-400' : 'dynamic-muted'))) }}">
                                    @if($paso2_ok)
                                        LISTO
                                    @elseif($paso2_cotizado)
                                        ESPERANDO AUTORIZACIÓN
                                    @elseif($estado === 'En_Proceso')
                                        EN REPARACIÓN
                                    @elseif($estado === 'Diagnosticando')
                                        DIAGNOSTICANDO
                                    @elseif($estado === 'Pendiente')
                                        EN COLA DE TALLER
                                    @elseif($esCancelado)
                                        CANCELADO
                                    @else
                                        AUN NO REALIZADO
                                    @endif
                                </span>
                                <div class="mb-2">
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.4-2.4c.4-.4.4-1 0-1.3z"/></svg>
                                </div>
                                <span class="font-bold block text-sm font-subtitulos">2. TÉCNICO</span>
                                <span class="text-[11px] block mt-1 opacity-90">Diagnóstico, cotización de piezas y ensamblado.</span>
                            </div>

                            <!-- PASO 3: RECEPCIÓN & CAJA -->
                            <div class="relative p-4 rounded border {{ $paso3_ok ? 'border-emerald-500 bg-emerald-950/25 text-emerald-400' : ($paso3_pend ? 'border-amber-400 bg-amber-950/25 text-amber-300' : 'admin-box opacity-60') }}">
                                <span class="absolute top-3 right-3 text-[10px] font-bold uppercase tracking-wider {{ $paso3_ok ? 'text-emerald-400' : ($paso3_pend ? 'text-amber-300' : 'dynamic-muted') }}">
                                    {{ $paso3_ok ? 'ENTREGADO' : ($paso3_pend ? 'LISTO PARA RETIRO' : 'AUN NO REALIZADO') }}
                                </span>
                                <div class="mb-2">
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M19 14V6c0-1.1-.9-2-2-2H3c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zm-9-1c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm13-6v11c0 1.1-.9 2-2 2H4v-2h17V7h2z"/></svg>
                                </div>
                                <span class="font-bold block text-sm font-subtitulos">3. RECEPCIÓN & CAJA</span>
                                <span class="text-[11px] block mt-1 opacity-90">Finiquito de liquidación, entrega física y ticket final.</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="admin-card p-8 rounded text-center">
                        <svg class="w-10 h-10 fill-current mx-auto mb-3 dynamic-muted" viewBox="0 0 24 24"><path d="M20 18c1.1 0 1.99-.9 1.99-2L22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 6h16v10H4V6z"/></svg>
                        <p class="text-sm font-bold dynamic-title">No tienes ningún equipo en proceso activo de reparación en este momento.</p>
                    </div>
                @endforelse
            </main>
        </div>
    </div>
</x-app-layout>