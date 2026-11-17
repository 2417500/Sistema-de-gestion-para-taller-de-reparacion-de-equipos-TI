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

                    <a href="{{ route('cliente.cotizaciones') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors dynamic-title hover:bg-[#588157]/40">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                            <span>COTIZACIONES PENDIENTES</span>
                        </div>
                    </a>

                    <a href="{{ route('cliente.historial') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors bg-[#588157] text-white font-bold">
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

            <!-- CONTENIDO PRINCIPAL: TABLA DE HISTORIAL DE DISPOSITIVOS Y TICKETS -->
            <main class="flex-grow p-6 space-y-6 text-xs overflow-y-auto">
                <div class="admin-card p-6 rounded border-l-4 border-[#588157] flex flex-wrap justify-between items-center gap-4">
                    <div>
                        <h1 class="dynamic-title text-lg font-bold uppercase tracking-wider font-titulos">Historial de Mis Pedidos y Servicios</h1>
                        <p class="dynamic-muted text-xs mt-1">Listado histórico de todos los equipos ingresados a Soporte Técnico.</p>
                    </div>
                    <div class="px-4 py-2 admin-box rounded font-bold font-mono">
                        TOTAL REGISTRADO: {{ $ordenes->count() }} EQUIPO(S)
                    </div>
                </div>

                <div class="admin-card p-4 rounded overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[11px] uppercase font-subtitulos">
                                <th class="py-3 px-3">Código</th>
                                <th class="py-3 px-3">Equipo / Modelo</th>
                                <th class="py-3 px-3">Serie</th>
                                <th class="py-3 px-3">Estado</th>
                                <th class="py-3 px-3">Total ($)</th>
                                <th class="py-3 px-3 text-center">Ticket de Entrada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ordenes as $ord)
                                <tr>
                                    <td class="py-3 px-3 font-bold dynamic-title font-mono">{{ $ord->codigo_servicio }}</td>
                                    <td class="py-3 px-3 dynamic-title">{{ $ord->marca }} {{ $ord->modelo }}</td>
                                    <td class="py-3 px-3 dynamic-muted font-mono">{{ $ord->numero_serie ?? 'N/A' }}</td>
                                    <td class="py-3 px-3">
                                        <span class="px-2.5 py-0.5 rounded border text-[10px] font-bold uppercase
                                            {{ $ord->estado === 'Terminado' ? 'border-emerald-400 text-emerald-400 bg-emerald-950/30' : '' }}
                                            {{ $ord->estado === 'En_Proceso' ? 'border-[#a3b18a] text-[#a3b18a] bg-[#588157]/20' : '' }}
                                            {{ $ord->estado === 'Pendiente' ? 'border-blue-400 text-blue-400 bg-blue-950/30' : '' }}
                                            {{ $ord->estado === 'Cancelado' ? 'border-rose-400 text-rose-400 bg-rose-950/30' : '' }}
                                            {{ $ord->estado === 'Entregado' ? 'border-purple-400 text-purple-400 bg-purple-950/30' : '' }}
                                        ">
                                            {{ str_replace('_', ' ', $ord->estado) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 font-bold dynamic-title font-mono">${{ number_format($ord->costo_total ?? 0, 2) }}</td>
                                    <td class="py-3 px-3 text-center">
                                        <a href="{{ route('recepcion.imprimir', $ord->id) }}" target="_blank" class="px-3 py-1.5 rounded admin-box dynamic-title hover:bg-[#588157] hover:text-white transition-colors inline-flex items-center gap-1.5 font-bold">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
                                            <span>Ver Ticket</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center dynamic-muted">No tienes ningún pedido o equipo registrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>