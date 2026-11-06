<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SoporTec Workbench') - Technical Noir</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,500;0,700;1,300&family=JetBrains+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Chakra Petch', sans-serif;
            background-color: #0d0f12;
            color: #c4c6cf;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        .amber-glow {
            box-shadow: 0 0 15px rgba(255, 180, 0, 0.2);
        }
    </style>
</head>
<body class="bg-[#0d0f12] text-[#c4c6cf] antialiased min-h-screen flex flex-col">

    <!-- MAIN WRAPPER -->
    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR DE NAVEGACIÓN TÉCNICA Y RESTRICCIÓN POR ROLES -->
        <aside class="w-64 bg-[#111318] border-r border-[#2d3038] flex flex-col justify-between shrink-0">
            <div>
                <!-- HEADER BRANDING -->
                <div class="p-4 border-b border-[#2d3038] flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <span class="px-2 py-1 bg-[#ffb800] text-[#0d0f12] font-mono font-black text-xs tracking-widest">SF</span>
                        <div class="flex flex-col">
                            <span class="text-white font-bold text-sm tracking-wider uppercase leading-none">TecnoFix</span>
                            <span class="text-[9px] font-mono text-[#ffb800] tracking-widest">SOPORTEC V4.2</span>
                        </div>
                    </a>
                    <span class="text-[10px] font-mono text-[#8a8e9b] bg-[#1a1c23] px-1.5 py-0.5 border border-[#2d3038]">TERMINAL</span>
                </div>

                <!-- ROLE BADGE INDICATOR -->
                <div class="p-3 bg-[#16181f] border-b border-[#2d3038] flex items-center justify-between">
                    <span class="text-[10px] font-mono text-[#8a8e9b] uppercase">ESTADO OPERATIVO</span>
                    <span class="px-2 py-0.5 bg-[#ffb800]/10 border border-[#ffb800]/40 text-[#ffb800] text-[10px] font-mono font-bold uppercase">
                        ROL: {{ Auth::user()->role->nombre ?? 'CLIENTE' }}
                    </span>
                </div>

                <!-- NAVEGACIÓN PRINCIPAL (FILTRADA EXACTAMENTE POR ROL) -->
                <nav class="p-3 space-y-1">
                    <div class="px-2 py-1.5 text-[10px] font-mono text-[#8a8e9b] uppercase tracking-wider flex justify-between items-center mb-1">
                        <span>MÓDULOS DEL SISTEMA</span>
                        <span class="text-[9px]">v4.2</span>
                    </div>

                    @php
                        $rolNombre = strtolower(trim(Auth::user()->role->nombre ?? 'cliente'));
                        $esAdmin = str_contains($rolNombre, 'admin');
                        $esRecepcion = str_contains($rolNombre, 'recepc');
                        $esTecnico = str_contains($rolNombre, 'tecnic') || str_contains($rolNombre, 'técnic');
                        $esAlmacen = str_contains($rolNombre, 'almac');
                        $esCliente = str_contains($rolNombre, 'client');
                    @endphp

                    <!-- 1. CREAR EMPLEADOS (EXCLUSIVO ADMINISTRADOR) -->
                    @if($esAdmin)
                        <a href="{{ route('admin.users.create') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded text-xs font-mono font-bold uppercase tracking-wider text-[#ffb800] bg-[#ffb800]/10 border border-[#ffb800]/30 hover:bg-[#ffb800]/20 transition-colors mb-2">
                            <span class="material-symbols-outlined text-base">person_add</span>
                            <span>Crear Empleados</span>
                        </a>
                    @endif

                    <!-- 2. DASHBOARD (ADMIN, RECEPCIÓN Y TÉCNICO) -->
                    @if($esAdmin || $esRecepcion || $esTecnico)
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded text-xs font-mono uppercase tracking-wider {{ request()->routeIs('dashboard') ? 'bg-[#ffb800] text-[#0d0f12] font-bold' : 'text-white hover:bg-[#1a1c23]' }} transition-colors">
                            <span class="material-symbols-outlined text-base">dashboard</span>
                            <span>Dashboard</span>
                        </a>
                    @endif

                    <!-- 3. RECEPCIÓN (EXCLUSIVO RECEPCIÓN) -->
                    @if($esRecepcion)
                        <a href="{{ route('recepcion.index') }}" 
                           class="flex items-center justify-between px-3 py-2 rounded text-xs font-mono transition-colors {{ request()->routeIs('recepcion.*') ? 'bg-[#ffb800]/20 text-[#ffb800] border border-[#ffb800]/40 font-bold' : 'text-[#c4c6cf] hover:bg-[#1a1c23] hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-base">move_to_inbox</span>
                                <span>Recepción</span>
                            </div>
                            <span class="text-[9px] bg-[#1a1c23] text-[#8a8e9b] px-1 border border-[#2d3038]">IN</span>
                        </a>
                    @endif

                    <!-- 4. BANCO TÉCNICO (ADMIN Y TÉCNICO) -->
                    @if($esAdmin || $esTecnico)
                        <a href="{{ route('banco.index') }}" 
                           class="flex items-center justify-between px-3 py-2 rounded text-xs font-mono transition-colors {{ request()->routeIs('banco.*') ? 'bg-[#ffb800]/20 text-[#ffb800] border border-[#ffb800]/40 font-bold' : 'text-[#c4c6cf] hover:bg-[#1a1c23] hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-base">build</span>
                                <span>Banco Técnico</span>
                            </div>
                            <span class="text-[9px] bg-[#1a1c23] text-[#ffb800] px-1 border border-[#ffb800]/30">FIX</span>
                        </a>
                    @endif

                    <!-- 5. ALMACÉN & STOCK (ADMIN Y ALMACÉN) -->
                    @if($esAdmin || $esAlmacen)
                        <a href="{{ route('almacen.index') }}" 
                           class="flex items-center justify-between px-3 py-2 rounded text-xs font-mono transition-colors {{ request()->routeIs('almacen.*') ? 'bg-[#ffb800]/20 text-[#ffb800] border border-[#ffb800]/40 font-bold' : 'text-[#c4c6cf] hover:bg-[#1a1c23] hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-base">inventory_2</span>
                                <span>Almacén & Stock</span>
                            </div>
                            <span class="text-[9px] bg-[#1a1c23] text-[#8a8e9b] px-1 border border-[#2d3038]">SKU</span>
                        </a>
                    @endif

                    <!-- 6. COTIZACIONES (ADMIN Y RECEPCIÓN) -->
                    @if($esAdmin || $esRecepcion)
                        <a href="{{ route('cotizaciones.index') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded text-xs font-mono transition-colors {{ request()->routeIs('cotizaciones.*') ? 'bg-[#ffb800]/20 text-[#ffb800] border border-[#ffb800]/40 font-bold' : 'text-[#c4c6cf] hover:bg-[#1a1c23] hover:text-white' }}">
                            <span class="material-symbols-outlined text-base">request_quote</span>
                            <span>Cotizaciones</span>
                        </a>
                    @endif

                    <!-- 7. FACTURACIÓN (ADMIN Y RECEPCIÓN) -->
                    @if($esAdmin || $esRecepcion)
                        <a href="#" class="flex items-center justify-between px-3 py-2 rounded text-xs font-mono text-[#c4c6cf] hover:bg-[#1a1c23] hover:text-white transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-base">receipt_long</span>
                                <span>Facturación</span>
                            </div>
                            <span class="text-[9px] bg-emerald-950 text-emerald-400 px-1 border border-emerald-500/30 font-bold">-15%</span>
                        </a>
                    @endif

                    <!-- 8. APROBACIONES (EXCLUSIVO ADMINISTRADOR) -->
                    @if($esAdmin)
                        <a href="{{ route('aprobaciones.index') }}" 
                           class="flex items-center justify-between px-3 py-2 rounded text-xs font-mono transition-colors {{ request()->routeIs('aprobaciones.*') ? 'bg-[#ffb800]/20 text-[#ffb800] border border-[#ffb800]/40 font-bold' : 'text-[#c4c6cf] hover:bg-[#1a1c23] hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-base">verified_user</span>
                                <span>Aprobaciones</span>
                            </div>
                            <span class="w-1.5 h-1.5 rounded-full bg-[#ffb800]"></span>
                        </a>
                    @endif

                    <!-- 9. AUDITORÍA (EXCLUSIVO ADMINISTRADOR) -->
                    @if($esAdmin)
                        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded text-xs font-mono text-[#c4c6cf] hover:bg-[#1a1c23] hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-base">history</span>
                            <span>Auditoría</span>
                        </a>
                    @endif

                    <!-- 10. MI EQUIPO / SEGUIMIENTO (EXCLUSIVO CLIENTE) -->
                    @if($esCliente)
                        <a href="{{ route('seguimiento') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded text-xs font-mono text-[#ffb800] bg-[#ffb800]/10 border border-[#ffb800]/30 hover:bg-[#ffb800]/20 transition-colors">
                            <span class="material-symbols-outlined text-base">devices</span>
                            <span>Mi Equipo en Taller</span>
                        </a>
                    @endif
                </nav>
            </div>

            <!-- FOOTER SIDEBAR STATUS -->
            <div class="p-3 border-t border-[#2d3038] bg-[#0d0f12] text-[10px] font-mono text-[#8a8e9b] space-y-1">
                <div class="flex justify-between">
                    <span>HARDWARE PROTOCOL:</span>
                    <span class="text-emerald-400 font-bold">ACTIVE</span>
                </div>
                <div class="flex justify-between">
                    <span>BUFFER DE SERVICIO:</span>
                    <span class="text-[#ffb800]">ST-NOIR-OK</span>
                </div>
            </div>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-[#0d0f12]">
            
            <!-- BARRA SUPERIOR DE TELEMETRÍA Y ACCIONES DE USUARIO -->
            <header class="h-14 bg-[#111318] border-b border-[#2d3038] px-6 flex items-center justify-between shrink-0">
                <!-- BUSCADOR RÁPIDO DE ÓRDENES -->
                <div class="flex items-center gap-4 flex-1 max-w-xl">
                    <form action="{{ url('/seguimiento') }}" method="GET" class="w-full relative">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-[#8a8e9b] text-sm">search</span>
                        <input type="text" name="codigo" placeholder="BUSCAR POR SERIAL, IMEI O CÓDIGO (ST-2026-XXXX)..." 
                               class="w-full bg-[#16181f] border border-[#2d3038] text-white font-mono text-xs pl-9 pr-12 py-1.5 focus:border-[#ffb800] focus:outline-none uppercase placeholder:text-[#5c606e]">
                        <button type="submit" class="absolute right-2 top-2 text-[10px] font-mono bg-[#1a1c23] text-[#8a8e9b] px-1.5 py-0.5 border border-[#2d3038]">
                            ENTER
                        </button>
                    </form>
                </div>

                <!-- USUARIO Y CONTROL DE SESIÓN -->
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 font-mono text-xs text-[#8a8e9b]">
                        <span class="material-symbols-outlined text-sm text-[#ffb800]">schedule</span>
                        <span>{{ now()->format('H:i:s') }}</span>
                    </div>

                    <div class="h-4 w-[1px] bg-[#2d3038]"></div>

                    <!-- USER INFO & LOGOUT -->
                    <div class="flex items-center gap-3">
                        <div class="text-right font-mono">
                            <div class="text-xs font-bold text-white uppercase">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] text-[#8a8e9b]">{{ Auth::user()->email }}</div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" title="Cerrar Sesión Terminal" 
                                    class="p-2 text-[#8a8e9b] hover:text-white hover:bg-[#1a1c23] border border-[#2d3038] rounded transition-colors flex items-center justify-center">
                                <span class="material-symbols-outlined text-base">power_settings_new</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- CONTENIDO DE PÁGINA -->
            <main class="p-6 flex-1">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>