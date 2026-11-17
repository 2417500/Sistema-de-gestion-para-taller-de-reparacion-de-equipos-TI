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

                    <a href="{{ route('cliente.historial') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors dynamic-title hover:bg-[#588157]/40">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
                            <span>HISTORIAL PEDIDOS</span>
                        </div>
                    </a>

                    <a href="{{ route('cliente.perfil') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors bg-[#588157] text-white font-bold">
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

            <!-- CONTENIDO PRINCIPAL: PERFIL -->
            <main class="flex-grow p-6 text-xs overflow-y-auto">
                <div class="max-w-3xl mx-auto space-y-6">
                    @if (session('success'))
                        <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded flex items-center gap-2">
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="admin-card p-8 rounded">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#588157]/50">
                            <svg class="w-7 h-7 fill-current text-[#a3b18a]" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                            <div>
                                <h2 class="text-lg font-bold dynamic-title uppercase tracking-wider font-titulos">
                                    Mi Perfil
                                </h2>
                                <p class="text-xs dynamic-muted">
                                    Consulta tus datos de registro y actualiza tu información personal.
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('cliente.perfil.update') }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block font-subtitulos uppercase dynamic-muted mb-2">Fecha de Registro en el Sistema</label>
                                <input type="text" value="{{ Auth::user()->created_at ? Auth::user()->created_at->format('d/m/Y H:i') : 'N/A' }}" readonly class="w-full rounded p-3 font-mono cursor-not-allowed opacity-80">
                            </div>

                            <div>
                                <label class="block font-subtitulos uppercase dynamic-muted mb-2">Nombre Completo / Usuario</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required class="w-full rounded p-3 outline-none">
                            </div>

                            <div>
                                <label class="block font-subtitulos uppercase dynamic-muted mb-2">Correo Electrónico</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required class="w-full rounded p-3 outline-none">
                            </div>

                            <div>
                                <label class="block font-subtitulos uppercase dynamic-muted mb-2">Número Telefónico</label>
                                <input type="text" name="telefono" value="{{ old('telefono', Auth::user()->cliente->telefono ?? Auth::user()->telefono ?? '') }}" required class="w-full rounded p-3 outline-none">
                            </div>

                            <div class="pt-4 border-t border-[#588157]/50 flex justify-end">
                                <button type="submit" class="px-6 py-3 rounded bg-[#588157] text-white font-bold font-subtitulos uppercase tracking-wider hover:bg-[#a3b18a] hover:text-[#1d2e26] transition-colors">
                                    Actualizar Mis Datos
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>