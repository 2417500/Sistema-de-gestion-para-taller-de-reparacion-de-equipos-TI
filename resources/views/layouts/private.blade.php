<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | TecnoFix Solutions</title>

    <!-- FUENTES OFICIALES (TITULOS: Amaranth | SUBTITULOS: Karla | TEXTO: Jost) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amaranth:ital,wght@0,400;0,700;1,400;1,700&family=Jost:ital,wght@0,100..900;1,100..900&family=Karla:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    <!-- TAILWIND CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        'dust-grey': '#dad7cd',
                        'dry-sage': '#a3b18a',
                        'fern': '#588157',
                        'hunter-green': '#3a5a40',
                        'pine-teal': '#344e41',
                    }
                }
            }
        }
    </script>

    <style>
        /* APLICACIÓN ESTRICTA DE TIPOGRAFÍAS */
        h1, h2, h3, .font-titulos { font-family: 'Amaranth', sans-serif !important; }
        h4, h5, h6, th, label, .font-subtitulos { font-family: 'Karla', sans-serif !important; }
        body, p, input, button, span, a, td, textarea, select, div { font-family: 'Jost', sans-serif !important; }

        /* ELIMINACIÓN FORZADA DE PALABRAS DE ICONOS EN TODOS LOS MENÚS */
        .material-symbols-outlined,
        .material-icons,
        span[class*="material-symbols"] {
            display: none !important;
            visibility: hidden !important;
            font-size: 0 !important;
            width: 0 !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* ==========================================================================
           MODO OSCURO GLOBAL (TONALIDAD PROFUNDA ESTILO WORKBENCH TÉCNICO)
           ========================================================================== */
        :root {
            --bg-main: #1d2e26;       /* Pine Teal Profundo */
            --bg-sidebar: #16241d;    /* Barra lateral profunda */
            --bg-card: #24382e;       /* Hunter Green Profundo */
            --bg-box: #18261f;        /* Cajas internas e inputs */
            --border-color: #588157;  /* Fern oficial para bordes nítidos */
            --text-title: #ffffff;
            --text-body: #dad7cd;     /* Dust Grey oficial */
            --text-muted: #a3b18a;    /* Dry Sage oficial */
            --table-header: #16241d;
        }

        /* ==========================================================================
           MODO CLARO GLOBAL (INTACTO + CONTRASTE DE ALERTAS CORREGIDO)
           ========================================================================== */
        html.light-mode {
            --bg-main: #dad7cd;
            --bg-sidebar: #a3b18a;
            --bg-card: #ffffff;
            --bg-box: #eef2e6;
            --border-color: #588157;
            --text-title: #1e3328;
            --text-body: #2c4236;
            --text-muted: #416350;
            --table-header: #eef2e6;
        }

        body {
            background-color: var(--bg-main) !important;
            color: var(--text-body) !important;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .admin-sidebar { 
            background-color: var(--bg-sidebar) !important; 
            border-color: var(--border-color) !important; 
        }

        .admin-card, .noir-card { 
            background-color: var(--bg-card) !important; 
            border: 1px solid var(--border-color) !important; 
            color: var(--text-body) !important; 
        }

        .admin-box { 
            background-color: var(--bg-box) !important; 
            border: 1px solid var(--border-color) !important; 
            color: var(--text-title) !important; 
        }

        .dynamic-title { color: var(--text-title) !important; }
        .dynamic-muted { color: var(--text-muted) !important; }

        table thead tr { 
            background-color: var(--table-header) !important; 
            color: var(--text-title) !important; 
        }

        table tbody tr { 
            border-bottom: 1px solid rgba(88, 129, 87, 0.45) !important; 
        }

        table tbody tr:hover { 
            background-color: var(--bg-box) !important; 
        }

        input, select, textarea { 
            background-color: var(--bg-box) !important; 
            border: 1px solid var(--border-color) !important; 
            color: var(--text-title) !important; 
        }

        /* CORRECCIÓN DE CONTRASTE EN MODO CLARO */
        html.light-mode main .text-white,
        html.light-mode main .text-gray-100,
        html.light-mode main .text-gray-200,
        html.light-mode main .text-gray-300,
        html.light-mode main .text-zinc-100,
        html.light-mode main .text-zinc-200,
        html.light-mode main .text-zinc-300,
        html.light-mode main .text-\[\#dad7cd\],
        html.light-mode main .text-\[\#e5e5e5\],
        html.light-mode main h1,
        html.light-mode main h2,
        html.light-mode main h3,
        html.light-mode main h4,
        html.light-mode main td,
        html.light-mode main th,
        html.light-mode main label,
        html.light-mode main p,
        html.light-mode main pre,
        html.light-mode main div {
            color: #1e3328 !important;
        }

        html.light-mode main .text-gray-400,
        html.light-mode main .text-gray-500,
        html.light-mode main .text-zinc-400,
        html.light-mode main .text-\[\#8a8e9b\],
        html.light-mode main .text-\[\#a3b18a\] {
            color: #3a5a40 !important;
        }

        /* ALERTAS DE ÉXITO Y ERROR LEGIBLES EN MODO CLARO */
        html.light-mode main [class*="bg-emerald-9"],
        html.light-mode main [class*="bg-green-9"] {
            background-color: #d8e8d4 !important;
            border-color: #3a5a40 !important;
            color: #143622 !important;
        }
        html.light-mode main [class*="bg-emerald-9"] *,
        html.light-mode main [class*="bg-green-9"] *,
        html.light-mode main .text-emerald-300,
        html.light-mode main .text-emerald-400,
        html.light-mode main .text-green-300,
        html.light-mode main .text-green-400 {
            color: #143622 !important;
        }

        html.light-mode main [class*="bg-rose-9"],
        html.light-mode main [class*="bg-red-9"] {
            background-color: #fee2e2 !important;
            border-color: #b91c1c !important;
            color: #7f1d1d !important;
        }
        html.light-mode main [class*="bg-rose-9"] *,
        html.light-mode main [class*="bg-red-9"] * {
            color: #7f1d1d !important;
        }

        html.light-mode main .bg-emerald-600,
        html.light-mode main .bg-emerald-600 *,
        html.light-mode main .bg-emerald-700,
        html.light-mode main .bg-emerald-700 *,
        html.light-mode main .bg-\[\#588157\],
        html.light-mode main .bg-\[\#588157\] *,
        html.light-mode main .bg-\[\#344e41\],
        html.light-mode main .bg-\[\#344e41\] *,
        html.light-mode main .bg-\[\#24382c\],
        html.light-mode main .bg-\[\#24382c\] * {
            color: #ffffff !important;
        }

        /* REEMPLAZO DE FONDOS NEGROS/GRISES OSCUROS POR EL TONO PROFUNDO DE PALETA */
        [class*="bg-[#0"], [class*="bg-[#1"], [class*="bg-[#20"], [class*="bg-[#21"], [class*="bg-[#22"], [class*="bg-[#23"],
        .bg-black, .bg-zinc-800, .bg-zinc-900, .bg-zinc-950,
        .bg-neutral-800, .bg-neutral-900, .bg-neutral-950,
        .bg-slate-800, .bg-slate-900, .bg-slate-950,
        .bg-gray-800, .bg-gray-900, .bg-gray-950,
        main pre, main code {
            background-color: var(--bg-box) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-title) !important;
        }

        html.light-mode [class*="bg-[#0"], html.light-mode [class*="bg-[#1"],
        html.light-mode .bg-black, html.light-mode .bg-zinc-800, html.light-mode .bg-zinc-900, html.light-mode .bg-zinc-950,
        html.light-mode .bg-neutral-800, html.light-mode .bg-neutral-900, html.light-mode .bg-neutral-950,
        html.light-mode .bg-slate-800, html.light-mode .bg-slate-900, html.light-mode .bg-slate-950,
        html.light-mode .bg-gray-800, html.light-mode .bg-gray-900, html.light-mode .bg-gray-950,
        html.light-mode main pre, html.light-mode main code {
            background-color: #eef2e6 !important;
            border: 1px solid #588157 !important;
            color: #1e3328 !important;
        }

        main a[class*="bg-[#1"]:hover, main button[class*="bg-[#1"]:hover,
        main a[class*="bg-[#0"]:hover, main button[class*="bg-[#0"]:hover,
        main button.bg-zinc-800:hover, main button.bg-gray-800:hover {
            background-color: #588157 !important;
            color: #ffffff !important;
        }

        /* REEMPLAZO DE AMARILLOS / NARANJAS / CAFÉS */
        [class*="text-[#ffb"], [class*="text-[#fca"], [class*="text-amber-"], [class*="text-yellow-"], [class*="text-orange-"] {
            color: #a3b18a !important;
        }
        html.light-mode [class*="text-[#ffb"], html.light-mode [class*="text-amber-"], html.light-mode [class*="text-yellow-"] {
            color: #3a5a40 !important;
        }

        [class*="bg-[#ffb"], [class*="bg-[#fca"], [class*="bg-amber-"], [class*="bg-yellow-"], [class*="bg-orange-"] {
            background-color: #588157 !important;
            color: #ffffff !important;
            border-color: #a3b18a !important;
            font-weight: 700 !important;
        }
        [class*="bg-[#ffb"]:hover, [class*="bg-amber-"]:hover, [class*="bg-yellow-"]:hover {
            background-color: #a3b18a !important;
            color: #1d2e26 !important;
        }

        [class*="border-[#ffb"], [class*="border-amber-"], [class*="border-yellow-"] {
            border-color: #588157 !important;
        }
    </style>
</head>
<body class="min-h-screen antialiased flex">

    @php
        $authUser = Auth::user();
        $rawRole  = $authUser->role ?? $authUser->rol ?? null;

        if (is_object($rawRole)) {
            $roleName = $rawRole->nombre ?? $rawRole->name ?? 'cliente';
        } elseif (is_array($rawRole)) {
            $roleName = $rawRole['nombre'] ?? $rawRole['name'] ?? 'cliente';
        } elseif (!empty($rawRole)) {
            $roleName = (string) $rawRole;
        } else {
            $roleById = [1 => 'administrador', 2 => 'recepcion', 3 => 'tecnico', 4 => 'almacen', 5 => 'cliente'];
            $roleName = $roleById[$authUser->role_id ?? $authUser->rol_id ?? 5] ?? 'cliente';
        }

        $userRole = strtolower(trim(str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
            ['a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u'],
            $roleName
        )));

        $roleLabels = [
            'admin'         => 'ADMINISTRADOR',
            'administrador' => 'ADMINISTRADOR',
            'recepcion'     => 'RECEPCIÓN',
            'recepcionista' => 'RECEPCIÓN',
            'tecnico'       => 'TÉCNICO',
            'almacen'       => 'ALMACÉN',
            'cliente'       => 'CLIENTE',
        ];

        $roleDisplay = $roleLabels[$userRole] ?? strtoupper($roleName);
        $isAdmin     = in_array($userRole, ['admin', 'administrador']);
        $isRecepcion = in_array($userRole, ['recepcion', 'recepcionista']);
        $isTecnico   = in_array($userRole, ['tecnico']);
        $isAlmacen   = in_array($userRole, ['almacen']);
    @endphp

    <!-- BARRA LATERAL NAVEGACIÓN CON ICONOS SVG POR ROL -->
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

            <!-- ETIQUETA LIMPIA DEL ROL ACTUAL -->
            <div class="px-2 py-1.5 bg-[#18261f] text-[#a3b18a] text-[10px] font-mono border border-[#588157] rounded text-center font-bold uppercase">
                ESTADO OPERATIVO // ROL: {{ $roleDisplay }}
            </div>

            <!-- MÓDULOS DE NAVEGACIÓN FILTRADOS POR ROL -->
            <nav class="space-y-1.5 font-subtitulos text-xs">

                {{-- SOLO ADMINISTRADOR: GESTIÓN DE PERSONAL --}}
                @if($isAdmin)
                    <a href="{{ url('/admin/personal') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors {{ request()->is('admin/personal*') || request()->is('admin/usuarios*') ? 'bg-[#588157] text-white font-bold' : 'dynamic-title hover:bg-[#588157]/40' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            <span>GESTIÓN DE PERSONAL</span>
                        </div>
                    </a>
                @endif

                {{-- DASHBOARD (ADMIN, RECEPCIÓN Y TÉCNICO; NO ALMACÉN) --}}
                @if(!$isAlmacen)
                    <a href="{{ url('/dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors {{ request()->is('dashboard') ? 'bg-[#588157] text-white font-bold' : 'dynamic-title hover:bg-[#588157]/40' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z"/></svg>
                            <span>DASHBOARD</span>
                        </div>
                    </a>
                @endif

                {{-- RECEPCIÓN Y ADMIN: INGRESO DE EQUIPO / NUEVA ORDEN (/recepcion) --}}
                @if($isRecepcion || $isAdmin)
                    <a href="{{ url('/recepcion') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors {{ request()->is('recepcion*') ? 'bg-[#588157] text-white font-bold' : 'dynamic-title hover:bg-[#588157]/40' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                            <span>Recepción / Nueva Orden</span>
                        </div>
                        <span class="px-1.5 py-0.5 bg-[#18261f] text-[#a3b18a] text-[9px] font-mono rounded">IN</span>
                    </a>
                @endif

                {{-- SOLO ADMIN Y TÉCNICO: BANCO TÉCNICO Y HERRAMIENTAS --}}
                @if($isAdmin || $isTecnico)
                    <a href="{{ url('/banco-tecnico') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors {{ request()->is('banco-tecnico') ? 'bg-[#588157] text-white font-bold' : 'dynamic-title hover:bg-[#588157]/40' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.4-2.4c.4-.4.4-1 0-1.3z"/></svg>
                            <span>Banco Técnico</span>
                        </div>
                        <span class="px-1.5 py-0.5 bg-[#18261f] text-[#a3b18a] text-[9px] font-mono rounded">FIX</span>
                    </a>

                    <a href="{{ url('/banco-tecnico/herramientas') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors {{ request()->is('*herramientas*') ? 'bg-[#588157] text-white font-bold' : 'dynamic-title hover:bg-[#588157]/40' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/></svg>
                            <span>Petición Herramientas</span>
                        </div>
                        <span class="px-1.5 py-0.5 bg-[#18261f] text-[#a3b18a] text-[9px] font-mono rounded">TOOL</span>
                    </a>
                @endif

                {{-- SOLO ADMIN Y ALMACÉN: ALMACÉN & STOCK --}}
                @if($isAdmin || $isAlmacen)
                    <a href="{{ url('/almacen') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors {{ request()->is('almacen*') ? 'bg-[#588157] text-white font-bold' : 'dynamic-title hover:bg-[#588157]/40' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4z"/></svg>
                            <span>Almacén & Stock</span>
                        </div>
                        <span class="px-1.5 py-0.5 bg-[#18261f] text-[#a3b18a] text-[9px] font-mono rounded">SKU</span>
                    </a>
                @endif

                {{-- RECEPCIÓN Y ADMIN: COTIZACIONES Y ENTREGAS --}}
                @if($isAdmin || $isRecepcion)
                    <a href="{{ url('/cotizaciones') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors {{ request()->is('cotizaciones*') ? 'bg-[#588157] text-white font-bold' : 'dynamic-title hover:bg-[#588157]/40' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                            <span>Cotizaciones</span>
                        </div>
                    </a>
                @endif

                {{-- RECEPCI?N, T?CNICO Y ADMIN: GARANT?AS --}}
                @if($isAdmin || $isRecepcion || $isTecnico)
                    <a href="{{ url('/garantias') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors {{ request()->is('garantias*') ? 'bg-[#588157] text-white font-bold' : 'dynamic-title hover:bg-[#588157]/40' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2L4 5v6.09c0 5.05 3.41 9.76 8 10.91 4.59-1.15 8-5.86 8-10.91V5l-8-3zm-1.06 13.54L7.4 12l1.41-1.41 2.12 2.12 4.24-4.24 1.41 1.41-5.64 5.66z"/></svg>
                            <span>Garantias</span>
                        </div>
                        <span class="px-1.5 py-0.5 bg-[#18261f] text-[#a3b18a] text-[9px] font-mono rounded">GAR</span>
                    </a>
                @endif

                {{-- SOLO ADMINISTRADOR: APROBACIONES --}}
                @if($isAdmin)
                    <a href="{{ url('/aprobaciones') }}" class="flex items-center justify-between px-3 py-2.5 rounded transition-colors {{ request()->is('aprobaciones*') ? 'bg-[#588157] text-white font-bold' : 'dynamic-title hover:bg-[#588157]/40' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-5.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                            <span>Aprobaciones</span>
                        </div>
                    </a>
                @endif
            </nav>
        </div>

        <!-- CONTROLES INFERIORES SIDENAV -->
        <div class="pt-4 border-t border-[#588157] space-y-3">
            <button onclick="toggleTheme()" class="w-full flex items-center gap-2.5 px-3 py-2 rounded text-xs font-subtitulos dynamic-title hover:bg-[#588157]/30 transition-colors">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2c5.52 0 10 4.48 10 10s-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2zm0 18c4.42 0 8-3.58 8-8s-3.58-8-8-8v16z"/></svg>
                <span>CAMBIAR MODO</span>
            </button>

            <div class="text-[10px] font-mono dynamic-muted">
                <div>HARDWARE PROTOCOL: <span class="text-emerald-400 font-bold">ACTIVE</span></div>
                <div>BUFFER DE SERVICIO: <span class="dynamic-title">ST-NOIR-OK</span></div>
            </div>
        </div>
    </aside>

    <!-- ÁREA DE CONTENIDO PRINCIPAL -->
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
                    <div class="font-bold uppercase dynamic-title">{{ Auth::user()->name ?? 'USUARIO SISTEMA' }}</div>
                    <div class="text-[10px] dynamic-muted">{{ Auth::user()->email ?? '' }}</div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 text-rose-400 hover:text-rose-600 transition-colors" title="Cerrar Sesión">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M13 3h-2v10h2V3zm4.83 2.17l-1.42 1.42C17.99 7.86 19 9.81 19 12c0 3.87-3.13 7-7 7s-7-3.13-7-7c0-2.19 1.01-4.14 2.58-5.42L6.17 5.17C4.23 6.82 3 9.26 3 12c0 4.97 4.03 9 9 9s9-4.03 9-9c0-2.74-1.23-5.18-3.17-6.83z"/></svg>
                    </button>
                </form>
            </div>
        </header>

        <!-- CONTENIDO DE CADA MÓDULO -->
        <main class="p-6 flex-grow">
            @yield('content')
        </main>

    </div>

    <!-- SCRIPT CAMBIO MODO -->
    <script>
        function toggleTheme() {
            document.documentElement.classList.toggle('light-mode');
            const isLight = document.documentElement.classList.contains('light-mode');
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
        }

        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.add('light-mode');
        }
    </script>
</body>
</html>