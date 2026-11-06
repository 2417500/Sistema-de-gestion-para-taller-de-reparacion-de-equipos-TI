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

                    <a href="{{ route('cliente.cotizaciones') }}" class="flex items-center gap-3 px-3 py-2 border border-transparent text-on-surface-variant hover:text-white hover:bg-surface-container hover:border-outline-variant text-xs uppercase transition-colors">
                        <span class="material-symbols-outlined text-base">pending_actions</span>
                        <span>COTIZACIONES PENDIENTES</span>
                    </a>

                    <a href="{{ route('cliente.historial') }}" class="flex items-center gap-3 px-3 py-2 bg-primary-container/10 border border-primary-container text-primary font-bold text-xs uppercase transition-colors">
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

        <!-- CONTENIDO PRINCIPAL: TABLA DE HISTORIAL DE DISPOSITIVOS Y TICKETS -->
        <main class="flex-grow p-6 space-y-6 font-mono text-xs overflow-y-auto">
            <div class="noir-card bg-surface-container-lowest p-6 border-l-2 border-primary-container flex justify-between items-center">
                <div>
                    <h1 class="text-white text-lg font-bold uppercase tracking-wider">Historial de Mis Pedidos y Servicios</h1>
                    <p class="text-on-surface-variant text-xs mt-1">Listado histórico de todos los equipos ingresados a Soporte Técnico.</p>
                </div>
                <div class="px-4 py-2 bg-surface-container border border-outline-variant text-primary-container font-bold">
                    TOTAL REGISTRADO: {{ $ordenes->count() }} EQUIPO(S)
                </div>
            </div>

            <div class="noir-card bg-surface-container-lowest p-4 border border-outline-variant overflow-x-auto">
                <table class="w-full text-left font-mono">
                    <thead>
                        <tr class="border-b border-outline-variant/60 text-on-surface-variant text-[11px] uppercase">
                            <th class="py-2 px-3">Código</th>
                            <th class="py-2 px-3">Equipo / Modelo</th>
                            <th class="py-2 px-3">Serie</th>
                            <th class="py-2 px-3">Estado</th>
                            <th class="py-2 px-3">Total ($)</th>
                            <th class="py-2 px-3 text-center">Ticket de Entrada</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30 text-white">
                        @forelse($ordenes as $ord)
                            <tr class="hover:bg-surface-container/50">
                                <td class="py-3 px-3 font-bold text-primary-container">{{ $ord->codigo_servicio }}</td>
                                <td class="py-3 px-3">{{ $ord->marca }} {{ $ord->modelo }}</td>
                                <td class="py-3 px-3 text-on-surface-variant">{{ $ord->numero_serie ?? 'N/A' }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 border text-[10px] font-bold uppercase
                                        {{ $ord->estado === 'Terminado' ? 'border-emerald-400 text-emerald-400 bg-emerald-950/30' : '' }}
                                        {{ $ord->estado === 'En_Proceso' ? 'border-amber-400 text-amber-400 bg-amber-950/30' : '' }}
                                        {{ $ord->estado === 'Pendiente' ? 'border-blue-400 text-blue-400 bg-blue-950/30' : '' }}
                                        {{ $ord->estado === 'Cancelado' ? 'border-rose-400 text-rose-400 bg-rose-950/30' : '' }}
                                        {{ $ord->estado === 'Entregado' ? 'border-purple-400 text-purple-400 bg-purple-950/30' : '' }}
                                    ">
                                        {{ str_replace('_', ' ', $ord->estado) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 font-bold text-white">${{ number_format($ord->costo_total ?? 0, 2) }}</td>
                                <td class="py-3 px-3 text-center">
                                    <a href="{{ route('recepcion.imprimir', $ord->id) }}" target="_blank" class="px-3 py-1 bg-surface-container border border-outline-variant text-on-surface-variant hover:text-white hover:border-primary-container transition-colors inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">print</span>
                                        <span>Ver Ticket</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-on-surface-variant">No tienes ningún pedido o equipo registrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</x-app-layout>