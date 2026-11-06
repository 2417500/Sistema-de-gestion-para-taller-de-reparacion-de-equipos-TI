<x-app-layout>
    <div class="flex min-h-[calc(100vh-4rem)]">
        <!-- MENÚ LATERAL TIPO WORKBENCH / INDUSTRIAL -->
        <aside class="w-64 bg-surface-container-lowest border-r border-outline-variant p-4 flex flex-col justify-between shrink-0 font-mono">
            <div class="space-y-6">
                <!-- CABECERA DEL MENÚ -->
                <div class="px-2 py-1 border-b border-outline-variant/60 pb-3">
                    <span class="text-[10px] text-on-surface-variant font-bold uppercase tracking-widest block">PORTAL CLIENTE</span>
                    <span class="text-xs text-primary font-bold uppercase tracking-wider block mt-0.5">SoporTec v4.2</span>
                </div>

                <!-- NAVEGACIÓN CLIENTE -->
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 border border-transparent text-on-surface-variant hover:text-white hover:bg-surface-container hover:border-outline-variant text-xs uppercase transition-colors">
                        <span class="material-symbols-outlined text-base">dashboard</span>
                        <span>DASHBOARD</span>
                    </a>

                    <a href="{{ route('cliente.cotizaciones') }}" class="flex items-center gap-3 px-3 py-2 bg-primary-container/10 border border-primary-container text-primary font-bold text-xs uppercase transition-colors">
                        <span class="material-symbols-outlined text-base">pending_actions</span>
                        <span>COTIZACIONES PENDIENTES</span>
                    </a>

                    <a href="{{ route('cliente.historial') }}" class="flex items-center gap-3 px-3 py-2 border border-transparent text-on-surface-variant hover:text-white hover:bg-surface-container hover:border-outline-variant text-xs uppercase transition-colors">
                        <span class="material-symbols-outlined text-base">history</span>
                        <span>HISTORIAL PEDIDOS</span>
                    </a>
                </nav>
            </div>

            <!-- FICHA TÉCNICA DEL USUARIO LOGUEADO -->
            <div class="p-3 bg-surface-container border border-outline-variant space-y-1">
                <span class="text-[10px] text-on-surface-variant uppercase block">SESIÓN ACTIVA</span>
                <span class="text-xs text-white font-bold block truncate">{{ Auth::user()->name }}</span>
                <span class="text-[10px] text-emerald-400 font-bold uppercase flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> CLIENTE AUTORIZADO
                </span>
            </div>
        </aside>

        <!-- CONTENIDO PRINCIPAL: REVISIÓN DE PRESUPUESTOS -->
        <main class="flex-grow p-6 space-y-6 font-mono text-xs overflow-y-auto">
            @if(session('success'))
                <div class="p-4 bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 rounded flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @php
                $cotizacionesPendientes = $ordenes->filter(function($o) {
                    return $o->estado === 'En_Proceso' && ($o->costo_total ?? 0) > 0 && !str_contains($o->falla_reportada ?? '', '[ESTADO COTIZACIÓN]: ACEPTADA');
                });
            @endphp

            @if($cotizacionesPendientes->count() > 0)
                <div class="space-y-4">
                    <h2 class="text-white text-sm font-bold uppercase tracking-wider flex items-center gap-2 text-[#ffb800]">
                        <span class="material-symbols-outlined text-base">pending_actions</span>
                        <span>COTIZACIONES PENDIENTES DE TU APROBACIÓN ({{ $cotizacionesPendientes->count() }})</span>
                    </h2>

                    @foreach($cotizacionesPendientes as $cotizacion)
                        <div class="noir-card bg-[#16181f] p-5 border border-[#ffb800]/50 space-y-4">
                            <div class="flex justify-between items-center border-b border-[#2d3038] pb-3">
                                <div>
                                    <span class="text-[#8a8e9b] block text-[10px]">CÓDIGO DE ORDEN</span>
                                    <span class="text-[#ffb800] text-sm font-bold">{{ $cotizacion->codigo_servicio }}</span>
                                </div>
                                <span class="px-3 py-1 bg-[#ffb800]/10 border border-[#ffb800] text-[#ffb800] text-[10px] font-bold uppercase">REVISA Y AUTORIZA</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-slate-300">
                                <div><span class="text-[#8a8e9b]">EQUIPO:</span> <strong class="text-white">{{ $cotizacion->marca }} {{ $cotizacion->modelo }}</strong></div>
                                <div><span class="text-[#8a8e9b]">N° SERIE:</span> <span class="text-white">{{ $cotizacion->numero_serie }}</span></div>
                                <div><span class="text-[#8a8e9b]">TIPO:</span> <span class="text-white">{{ $cotizacion->tipo_equipo }}</span></div>
                            </div>

                            <div class="bg-[#0d0f12] p-3 border border-[#2d3038]">
                                <span class="text-[#8a8e9b] block mb-1">DIAGNÓSTICO Y NOTAS DEL TÉCNICO:</span>
                                <p class="text-white whitespace-pre-line">{{ $cotizacion->falla_reportada }}</p>
                            </div>

                            <div class="bg-[#0d0f12] p-4 border border-[#2d3038] space-y-3">
                                <div class="flex justify-between border-b border-[#2d3038] pb-2">
                                    <span class="text-[#8a8e9b]">Mano de Obra Especializada:</span>
                                    <span class="text-white font-bold">${{ number_format($cotizacion->costo_mano_obra ?? 0, 2) }} MXN</span>
                                </div>

                                @if(isset($refaccionesGrouped[$cotizacion->id]) && count($refaccionesGrouped[$cotizacion->id]) > 0)
                                    <div class="border-b border-[#2d3038] pb-2">
                                        <span class="text-[#8a8e9b] block mb-1">Refacciones e Insumos Cotizados:</span>
                                        <ul class="space-y-1 pl-2 text-slate-300">
                                            @foreach($refaccionesGrouped[$cotizacion->id] as $ref)
                                                <li class="flex justify-between">
                                                    <span>• {{ $ref->nombre }} (x{{ $ref->cantidad }})</span>
                                                    <span class="text-white font-bold">${{ number_format($ref->precio_unitario * $ref->cantidad, 2) }} MXN</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="flex justify-between text-sm font-bold pt-1">
                                    <span class="text-white">TOTAL ESTIMADO DE REPARACIÓN:</span>
                                    <span class="text-[#ffb800]">${{ number_format($cotizacion->costo_total ?? 0, 2) }} MXN</span>
                                </div>
                                <div class="flex justify-between text-xs text-emerald-400 font-bold">
                                    <span>ANTICIPO REQUERIDO (50%):</span>
                                    <span>${{ number_format(($cotizacion->costo_total ?? 0) * 0.50, 2) }} MXN</span>
                                </div>
                            </div>

                            <form action="{{ route('cotizacion.responder') }}" method="POST" class="space-y-3 pt-2">
                                @csrf
                                <input type="hidden" name="orden_id" value="{{ $cotizacion->id }}">
                                <textarea name="nota_cliente" placeholder="Escribe un comentario o indicación opcional para el taller..." class="w-full bg-[#0d0f12] border border-[#2d3038] text-white p-2 text-xs focus:border-[#ffb800] outline-none"></textarea>
                                
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <button type="submit" name="accion" value="aceptar" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold uppercase tracking-wider transition-colors flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                        <span>Aceptar Cotización</span>
                                    </button>
                                    <button type="submit" name="accion" value="rechazar" class="flex-1 py-3 bg-rose-600 hover:bg-rose-500 text-white font-bold uppercase tracking-wider transition-colors flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-sm">cancel</span>
                                        <span>Rechazar Cotización</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="noir-card bg-surface-container-lowest p-8 text-center text-on-surface-variant border border-outline-variant">
                    <span class="material-symbols-outlined text-3xl block mb-2 text-emerald-500">check_circle</span>
                    <p class="text-sm font-bold text-white">No tienes cotizaciones pendientes por autorizar.</p>
                </div>
            @endif
        </main>
    </div>
</x-app-layout>