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
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors dynamic-title hover:bg-[#588157]/40">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z"/></svg>
                            <span>DASHBOARD</span>
                        </div>
                    </a>

                    <a href="{{ route('cliente.cotizaciones') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors bg-[#588157] text-white font-bold">
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

            <!-- CONTENIDO PRINCIPAL: REVISIÓN DE PRESUPUESTOS -->
            <main class="flex-grow p-6 space-y-6 text-xs overflow-y-auto">
                @if(session('success'))
                    <div class="p-4 bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 rounded flex items-center gap-2">
                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @php
                    $cotizacionesPendientes = $ordenes->filter(function($o) {
                        $esCotizada = in_array($o->estado, ['Cotizado', 'En_Proceso']);
                        return $esCotizada && ($o->costo_total ?? 0) > 0 && !str_contains($o->falla_reportada ?? '', '[ESTADO COTIZACIÓN]: ACEPTADA');
                    });
                @endphp

                @if($cotizacionesPendientes->count() > 0)
                    <div class="space-y-4">
                        <h2 class="dynamic-title text-base font-bold uppercase tracking-wider flex items-center gap-2 font-titulos">
                            <svg class="w-5 h-5 fill-current text-[#a3b18a]" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                            <span>COTIZACIONES PENDIENTES DE TU APROBACIÓN ({{ $cotizacionesPendientes->count() }})</span>
                        </h2>

                        @foreach($cotizacionesPendientes as $cotizacion)
                            @php
                                $fotosDiag = is_string($cotizacion->fotos_diagnostico ?? null)
                                    ? json_decode($cotizacion->fotos_diagnostico, true)
                                    : ($cotizacion->fotos_diagnostico ?? []);
                            @endphp

                            <div class="admin-card p-5 rounded space-y-4">
                                <div class="flex flex-wrap justify-between items-center border-b border-[#588157]/50 pb-3 gap-2">
                                    <div>
                                        <span class="dynamic-muted block text-[10px] font-subtitulos">CÓDIGO DE ORDEN</span>
                                        <span class="dynamic-title text-sm font-bold font-mono">{{ $cotizacion->codigo_servicio }}</span>
                                    </div>
                                    <span class="px-3 py-1 bg-[#588157] text-white rounded text-[10px] font-bold uppercase">REVISA Y AUTORIZA</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div><span class="dynamic-muted">EQUIPO:</span> <strong class="dynamic-title">{{ $cotizacion->marca }} {{ $cotizacion->modelo }}</strong></div>
                                    <div><span class="dynamic-muted">N° SERIE:</span> <span class="dynamic-title font-mono">{{ $cotizacion->numero_serie }}</span></div>
                                    <div><span class="dynamic-muted">TIPO:</span> <span class="dynamic-title">{{ $cotizacion->tipo_equipo }}</span></div>
                                </div>

                                <!-- DIAGNÓSTICO Y NOTAS DEL TÉCNICO -->
                                <div class="admin-box p-3 rounded space-y-3">
                                    <div>
                                        <span class="dynamic-muted block mb-1 font-subtitulos">DIAGNÓSTICO Y NOTAS DEL TÉCNICO:</span>
                                        <p class="dynamic-title whitespace-pre-line">{{ $cotizacion->falla_reportada }}</p>
                                    </div>

                                    @if(!empty($cotizacion->observaciones_taller))
                                        <div class="pt-3 border-t border-[#588157]/40">
                                            <span class="text-amber-400 block mb-1 font-subtitulos font-bold uppercase">OBSERVACIONES DE INGRESO AL TALLER (TÉCNICO):</span>
                                            <p class="dynamic-title whitespace-pre-line">{{ $cotizacion->observaciones_taller }}</p>
                                        </div>
                                    @endif

                                    @if(is_array($fotosDiag) && count($fotosDiag) > 0)
                                        <div class="pt-3 border-t border-[#588157]/40">
                                            <span class="text-[#a3b18a] block mb-2 font-subtitulos font-bold uppercase">EVIDENCIAS FOTOGRÁFICAS DEL TALLER ({{ count($fotosDiag) }}):</span>
                                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                                @foreach($fotosDiag as $foto)
                                                    <a href="{{ asset('storage/' . $foto) }}" target="_blank" class="block border border-[#588157]/60 rounded overflow-hidden hover:border-emerald-400 transition-colors">
                                                        <img src="{{ asset('storage/' . $foto) }}" alt="Evidencia Técnica" class="w-full h-28 object-cover">
                                                    </a>
                                                @endforeach
                                            </div>
                                            <span class="text-[10px] dynamic-muted block mt-1">Haz clic en cualquier imagen para ampliarla en tamaño completo.</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- DESGLOSE FINANCIERO -->
                                <div class="admin-box p-4 rounded space-y-3">
                                    <div class="flex justify-between border-b border-[#588157]/40 pb-2">
                                        <span class="dynamic-muted">Mano de Obra Especializada:</span>
                                        <span class="dynamic-title font-bold font-mono">${{ number_format($cotizacion->costo_mano_obra ?? 0, 2) }} MXN</span>
                                    </div>

                                    @if(isset($refaccionesGrouped[$cotizacion->id]) && count($refaccionesGrouped[$cotizacion->id]) > 0)
                                        <div class="border-b border-[#588157]/40 pb-2">
                                            <span class="dynamic-muted block mb-1 font-subtitulos">Refacciones e Insumos Cotizados:</span>
                                            <ul class="space-y-1 pl-2">
                                                @foreach($refaccionesGrouped[$cotizacion->id] as $ref)
                                                    <li class="flex justify-between">
                                                        <span class="dynamic-title">{{ $ref->nombre }} (x{{ $ref->cantidad }})</span>
                                                        <span class="dynamic-title font-bold font-mono">${{ number_format($ref->precio_unitario * $ref->cantidad, 2) }} MXN</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="flex justify-between text-sm font-bold pt-1">
                                        <span class="dynamic-title">TOTAL ESTIMADO DE REPARACIÓN:</span>
                                        <span class="dynamic-title font-mono">${{ number_format($cotizacion->costo_total ?? 0, 2) }} MXN</span>
                                    </div>
                                    <div class="flex justify-between text-xs text-emerald-400 font-bold">
                                        <span>ANTICIPO REQUERIDO (50%):</span>
                                        <span class="font-mono">${{ number_format(($cotizacion->costo_total ?? 0) * 0.50, 2) }} MXN</span>
                                    </div>
                                </div>

                                <form action="{{ route('cotizacion.responder') }}" method="POST" class="space-y-3 pt-2">
                                    @csrf
                                    <input type="hidden" name="orden_id" value="{{ $cotizacion->id }}">
                                    <textarea name="nota_cliente" rows="2" placeholder="Escribe un comentario o indicación opcional para el taller..." class="w-full rounded p-2.5 text-xs outline-none"></textarea>
                                    
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <button type="submit" name="accion" value="aceptar" class="flex-1 py-3 rounded bg-emerald-600 hover:bg-emerald-500 text-white font-bold uppercase tracking-wider transition-colors flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                            <span>Aceptar Cotización</span>
                                        </button>
                                        <button type="submit" name="accion" value="rechazar" class="flex-1 py-3 rounded bg-rose-600 hover:bg-rose-500 text-white font-bold uppercase tracking-wider transition-colors flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
                                            <span>Rechazar Cotización</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="admin-card p-8 rounded text-center">
                        <svg class="w-10 h-10 fill-current mx-auto mb-3 text-emerald-400" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        <p class="text-sm font-bold dynamic-title">No tienes cotizaciones pendientes por autorizar.</p>
                    </div>
                @endif
            </main>
        </div>
    </div>
</x-app-layout>