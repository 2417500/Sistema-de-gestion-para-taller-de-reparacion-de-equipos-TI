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

                <!-- NAVEGACIÓN CLIENTE EN 3 MENÚS DEDICADOS -->
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 bg-primary-container/10 border border-primary-container text-primary font-bold text-xs uppercase transition-colors">
                        <span class="material-symbols-outlined text-base">dashboard</span>
                        <span>DASHBOARD</span>
                    </a>

                    <a href="{{ route('cliente.cotizaciones') }}" class="flex items-center gap-3 px-3 py-2 border border-transparent text-on-surface-variant hover:text-white hover:bg-surface-container hover:border-outline-variant text-xs uppercase transition-colors">
                        <span class="material-symbols-outlined text-base">pending_actions</span>
                        <span>COTIZACIONES PENDIENTES</span>
                    </a>

                    <a href="{{ route('cliente.historial') }}" class="flex items-center gap-3 px-3 py-2 border border-transparent text-on-surface-variant hover:text-white hover:bg-surface-container hover:border-outline-variant text-xs uppercase transition-colors">
                        <span class="material-symbols-outlined text-base">history</span>
                        <span>HISTORIAL PEDIDOS</span>
                    </a>
                </nav>
            </div>

            <!-- FICHA TÉCNICA DEL USUARIO LOGUEADO EN SIDEBAR -->
            <div class="p-3 bg-surface-container border border-outline-variant space-y-1">
                <span class="text-[10px] text-on-surface-variant uppercase block">SESIÓN ACTIVA</span>
                <span class="text-xs text-white font-bold block truncate">{{ Auth::user()->name }}</span>
                <span class="text-[10px] text-emerald-400 font-bold uppercase flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> CLIENTE AUTORIZADO
                </span>
            </div>
        </aside>

        <!-- CONTENIDO PRINCIPAL: RASTREADOR DE ESTADO PARA TODOS LOS DISPOSITIVOS -->
        <main class="flex-grow p-6 space-y-6 font-mono text-xs overflow-y-auto">
            <div class="noir-card bg-surface-container-lowest p-6 border-l-2 border-primary-container flex justify-between items-center">
                <div>
                    <h1 class="text-white text-lg font-bold uppercase tracking-wider">Estado Actual de sus Dispositivos // {{ Auth::user()->name }}</h1>
                    <p class="text-on-surface-variant text-xs mt-1">Seguimiento en tiempo real de todas las órdenes activas en taller de reparación.</p>
                </div>
                @if(isset($ordenes) && $ordenes->count() > 0)
                    <div class="px-4 py-2 bg-surface-container border border-outline-variant text-primary-container font-bold">
                        TOTAL DISPOSITIVOS: {{ $ordenes->count() }}
                    </div>
                @endif
            </div>

            @forelse($ordenes ?? [] as $orden)
                <div class="noir-card bg-[#16181f] p-6 border border-outline-variant space-y-6">
                    <div class="flex justify-between items-center border-b border-outline-variant pb-3">
                        <div>
                            <span class="text-on-surface-variant block text-[10px]">CÓDIGO DE ORDEN ACTIVA</span>
                            <span class="text-primary-container text-base font-bold">{{ $orden->codigo_servicio }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-on-surface-variant block text-[10px]">EQUIPO REGISTRADO</span>
                            <span class="text-white font-bold uppercase">{{ $orden->marca }} {{ $orden->modelo }} ({{ $orden->numero_serie }})</span>
                        </div>
                    </div>

                    <!-- LÍNEA DE TIEMPO DEL PROCESO OPERATIVO -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                        <!-- PASO 1: RECEPCIÓN -->
                        @php
                            $paso1_ok = in_array($orden->estado, ['Pendiente', 'En_Proceso', 'Terminado', 'Entregado']);
                        @endphp
                        <div class="relative p-4 border {{ $paso1_ok ? 'border-emerald-500 bg-emerald-950/20 text-emerald-400' : 'border-outline-variant text-slate-500' }}">
                            <span class="absolute top-2 right-2 text-[10px] font-bold uppercase tracking-wider {{ $paso1_ok ? 'text-emerald-400' : 'text-slate-500' }}">
                                {{ $paso1_ok ? 'LISTO' : 'AUN NO REALIZADO' }}
                            </span>
                            <span class="material-symbols-outlined text-2xl block mb-1">storefront</span>
                            <span class="font-bold block text-sm">1. RECEPCIÓN</span>
                            <span class="text-[10px] block mt-1">Registro formal de la orden e ingreso al taller.</span>
                        </div>

                        <!-- PASO 2: TÉCNICO -->
                        @php
                            $paso2_ok = in_array($orden->estado, ['Terminado', 'Entregado']);
                            $paso2_pend = in_array($orden->estado, ['En_Proceso', 'Pendiente']);
                        @endphp
                        <div class="relative p-4 border {{ $paso2_ok ? 'border-emerald-500 bg-emerald-950/20 text-emerald-400' : ($paso2_pend ? 'border-amber-500 bg-amber-950/20 text-amber-400' : 'border-outline-variant text-slate-500') }}">
                            <span class="absolute top-2 right-2 text-[10px] font-bold uppercase tracking-wider {{ $paso2_ok ? 'text-emerald-400' : ($paso2_pend ? 'text-amber-400' : 'text-slate-500') }}">
                                {{ $paso2_ok ? 'LISTO' : ($paso2_pend ? 'PENDIENTE' : 'AUN NO REALIZADO') }}
                            </span>
                            <span class="material-symbols-outlined text-2xl block mb-1">build</span>
                            <span class="font-bold block text-sm">2. TÉCNICO</span>
                            <span class="text-[10px] block mt-1">Diagnóstico, cotización de piezas y ensamblado.</span>
                        </div>

                        <!-- PASO 3: RECEPCIÓN & CAJA -->
                        @php
                            $paso3_ok = ($orden->estado === 'Entregado');
                            $paso3_pend = ($orden->estado === 'Terminado');
                        @endphp
                        <div class="relative p-4 border {{ $paso3_ok ? 'border-emerald-500 bg-emerald-950/20 text-emerald-400' : ($paso3_pend ? 'border-amber-500 bg-amber-950/20 text-amber-400' : 'border-outline-variant text-slate-500') }}">
                            <span class="absolute top-2 right-2 text-[10px] font-bold uppercase tracking-wider {{ $paso3_ok ? 'text-emerald-400' : ($paso3_pend ? 'text-amber-400' : 'text-slate-500') }}">
                                {{ $paso3_ok ? 'LISTO' : ($paso3_pend ? 'PENDIENTE' : 'AUN NO REALIZADO') }}
                            </span>
                            <span class="material-symbols-outlined text-2xl block mb-1">payments</span>
                            <span class="font-bold block text-sm">3. RECEPCIÓN & CAJA</span>
                            <span class="text-[10px] block mt-1">Finiquito de liquidación, entrega física y ticket final.</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="noir-card bg-surface-container-lowest p-8 text-center text-on-surface-variant border border-outline-variant">
                    <span class="material-symbols-outlined text-3xl block mb-2 text-slate-600">devices</span>
                    <p class="text-sm font-bold text-slate-400">No tienes ningún equipo en proceso activo de reparación en este momento.</p>
                </div>
            @endforelse
        </main>
    </div>
</x-app-layout>