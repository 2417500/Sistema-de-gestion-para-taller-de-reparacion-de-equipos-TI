<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Público') | TecnoFix Solutions - SoporTec v4.2</title>

    <!-- Prevención de destello de tema (Anti-Flicker Script) -->
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>

    <!-- Google Fonts: Space Grotesk, Hanken Grotesk, JetBrains Mono, Josefin Sans & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,300..700;1,300..700&family=JetBrains+Mono:wght@400;500;600;700&family=Josefin+Sans:wght@600;700&family=Space+Grotesk:wght@500;600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v3 via CDN con Container Queries & Forms -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "noir-black": "#000000",
                        "noir-blue": "#14213d",
                        "noir-amber": "#fca311",
                        "noir-grey": "#e5e5e5",
                        "noir-white": "#ffffff",
                        "background": "#131313",
                        "surface": "#131313",
                        "surface-dim": "#131313",
                        "surface-bright": "#393939",
                        "surface-container-lowest": "#0e0e0e",
                        "surface-container-low": "#1b1b1b",
                        "surface-container": "#1f1f1f",
                        "surface-container-high": "#2a2a2a",
                        "surface-container-highest": "#353535",
                        "on-surface": "#e2e2e2",
                        "on-surface-variant": "#d9c3ad",
                        "outline": "#a18e79",
                        "outline-variant": "rgba(229, 229, 229, 0.12)",
                        "primary": "#ffc887",
                        "primary-container": "#fca311",
                        "on-primary": "#472a00",
                        "on-primary-container": "#663f00",
                        "secondary": "#b9c6ea",
                        "secondary-container": "#14213d",
                        "on-secondary-container": "#abb8db",
                        "error": "#ffb4ab",
                        "error-container": "#93000a"
                    },
                    fontFamily: {
                        "headline-xl": ["Space Grotesk", "sans-serif"],
                        "headline-lg": ["Space Grotesk", "sans-serif"],
                        "headline-md": ["Space Grotesk", "sans-serif"],
                        "body-lg": ["Hanken Grotesk", "sans-serif"],
                        "body-md": ["Hanken Grotesk", "sans-serif"],
                        "body-sm": ["Hanken Grotesk", "sans-serif"],
                        "label-lg": ["JetBrains Mono", "monospace"],
                        "label-md": ["JetBrains Mono", "monospace"],
                        "label-sm": ["JetBrains Mono", "monospace"],
                        "josefin": ["Josefin Sans", "sans-serif"]
                    }
                }
            }
        }
    </script>

    <style>
        .film-grid {
            background-size: 24px 24px;
            background-image: 
                linear-gradient(to right, rgba(229, 229, 229, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(229, 229, 229, 0.03) 1px, transparent 1px);
        }
        .noir-card {
            border: 1px solid rgba(229, 229, 229, 0.12);
            box-shadow: 0 4px 0px 0px rgba(0, 0, 0, 0.85);
            border-radius: 0px !important;
        }
        .amber-glow {
            box-shadow: 0 0 10px rgba(252, 163, 17, 0.28);
        }
        .amber-line {
            border-top: 2px solid #fca311;
        }
        .table-cell-mono {
            font-family: 'JetBrains Mono', monospace;
            font-variant-numeric: tabular-nums;
        }
        .thermal-receipt {
            background: #fdfbf7;
            color: #111111;
            font-family: 'JetBrains Mono', monospace;
            box-shadow: 0 8px 16px rgba(0,0,0,0.4);
            border-radius: 0px;
        }
        * {
            border-radius: 0px !important;
        }

        /* MODO CLARO / INVERSIÓN DIAGNÓSTICA (TECHNICAL NOIR WORKSHOP) */
        html:not(.dark) {
            color-scheme: light;
        }
        html:not(.dark) body {
            background-color: #f4f4f5 !important;
            color: #09090b !important;
        }
        html:not(.dark) .film-grid {
            background-image: 
                linear-gradient(to right, rgba(20, 33, 61, 0.06) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(20, 33, 61, 0.06) 1px, transparent 1px) !important;
        }
        html:not(.dark) header,
        html:not(.dark) footer,
        html:not(.dark) aside,
        html:not(.dark) .bg-background {
            background-color: #f4f4f5 !important;
        }
        html:not(.dark) .bg-surface-container-lowest,
        html:not(.dark) .bg-surface {
            background-color: #ffffff !important;
            border-color: rgba(20, 33, 61, 0.18) !important;
        }
        html:not(.dark) .bg-surface-container-low {
            background-color: #fafafa !important;
            border-color: rgba(20, 33, 61, 0.18) !important;
        }
        html:not(.dark) .bg-surface-container {
            background-color: #f4f4f5 !important;
            border-color: rgba(20, 33, 61, 0.15) !important;
        }
        html:not(.dark) .bg-surface-container-high {
            background-color: #e4e4e7 !important;
            border-color: rgba(20, 33, 61, 0.2) !important;
        }
        html:not(.dark) .bg-surface-container-highest {
            background-color: #d4d4d8 !important;
            border-color: rgba(20, 33, 61, 0.2) !important;
        }
        html:not(.dark) .noir-card {
            background-color: #ffffff !important;
            border-color: rgba(20, 33, 61, 0.18) !important;
            box-shadow: 0 4px 0px 0px rgba(20, 33, 61, 0.10) !important;
        }
        html:not(.dark) .border-outline-variant,
        html:not(.dark) .border-outline {
            border-color: rgba(20, 33, 61, 0.18) !important;
        }
        html:not(.dark) .text-white,
        html:not(.dark) .text-on-surface {
            color: #09090b !important;
        }
        html:not(.dark) .text-on-surface-variant {
            color: #52525b !important;
        }
        html:not(.dark) .text-primary,
        html:not(.dark) .text-primary-container {
            color: #b45309 !important;
        }
        html:not(.dark) .bg-primary-container {
            background-color: #d97706 !important;
            color: #ffffff !important;
            border-color: #d97706 !important;
        }
        html:not(.dark) .bg-primary-container * {
            color: #ffffff !important;
        }
        html:not(.dark) .bg-primary-container:hover {
            background-color: #14213d !important;
            color: #ffffff !important;
            border-color: #14213d !important;
        }
        html:not(.dark) .bg-noir-blue {
            background-color: #14213d !important;
            color: #ffffff !important;
        }
        html:not(.dark) .bg-noir-blue .text-white,
        html:not(.dark) .bg-noir-blue * {
            color: #ffffff !important;
        }
        html:not(.dark) thead tr.bg-noir-blue th {
            background-color: #14213d !important;
            color: #ffffff !important;
        }
        html:not(.dark) .amber-line {
            border-top-color: #d97706 !important;
        }
        html:not(.dark) .amber-glow {
            box-shadow: 0 0 10px rgba(217, 119, 6, 0.25) !important;
        }
        html:not(.dark) input,
        html:not(.dark) textarea,
        html:not(.dark) select {
            background-color: #ffffff !important;
            color: #09090b !important;
            border-color: rgba(20, 33, 61, 0.25) !important;
        }
        html:not(.dark) input::placeholder,
        html:not(.dark) textarea::placeholder {
            color: #71717a !important;
        }
        html:not(.dark) input:focus,
        html:not(.dark) textarea:focus,
        html:not(.dark) select:focus {
            border-color: #d97706 !important;
            box-shadow: 0 0 0 1px #d97706 !important;
        }
        html:not(.dark) table {
            border-color: rgba(20, 33, 61, 0.18) !important;
        }
        html:not(.dark) tbody tr:hover {
            background-color: #f4f4f5 !important;
        }
        html:not(.dark) .hover\:bg-surface-container:hover {
            background-color: #e4e4e7 !important;
        }
        html:not(.dark) .table-cell-mono {
            color: #09090b !important;
        }
        html:not(.dark) .hover\:text-white:hover {
            color: #09090b !important;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-background text-on-surface antialiased font-body-md film-grid min-h-screen flex flex-col selection:bg-primary-container selection:text-noir-black transition-colors duration-200">

    <!-- BARRA DE NAVEGACIÓN PÚBLICA -->
    <header class="fixed top-0 left-0 right-0 h-16 z-40 bg-surface-container-lowest/95 backdrop-blur-md border-b border-outline-variant flex items-center justify-between px-4 lg:px-8">
        <!-- Logotipo y Nombre del Sistema -->
        <div class="flex items-center gap-6">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="w-8 h-8 bg-noir-blue border border-primary-container flex items-center justify-center font-josefin font-bold text-primary-container text-base group-hover:bg-primary-container group-hover:text-noir-black transition-colors">
                    SF
                </div>
                <div>
                    <span class="text-headline-md font-headline-md font-bold tracking-wider text-primary-container uppercase">TecnoFix Solutions</span>
                    <span class="hidden sm:inline-block ml-2 px-1.5 py-0.5 text-[9px] font-label-sm uppercase bg-surface-container-high text-primary border border-outline-variant">SoporTec v4.2</span>
                </div>
            </a>

            <!-- Enlaces de Navegación Pública -->
            <nav class="hidden md:flex items-center gap-6 pl-6 border-l border-outline-variant">
                <a href="{{ url('/#taller') }}" class="text-on-surface-variant font-label-sm text-label-sm uppercase hover:text-primary-container transition-colors">Taller</a>
                <a href="{{ url('/#seguimiento') }}" class="text-on-surface-variant font-label-sm text-label-sm uppercase hover:text-primary-container transition-colors">Seguimiento</a>
                <a href="{{ url('/#horarios') }}" class="text-on-surface-variant font-label-sm text-label-sm uppercase hover:text-primary-container transition-colors">Horarios</a>
                <a href="{{ url('/#politicas') }}" class="text-on-surface-variant font-label-sm text-label-sm uppercase hover:text-primary-container transition-colors">Políticas</a>
                <a href="{{ url('/#ubicacion') }}" class="text-on-surface-variant font-label-sm text-label-sm uppercase hover:text-primary-container transition-colors">Ubicación</a>
            </nav>
        </div>

        <!-- Acciones Trailing: Consulta Rápida de Ticket, Cambio de Tema & Login -->
        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-2 px-2.5 py-1 bg-surface-container border border-outline-variant font-label-sm text-xs text-on-surface-variant">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>TALLER OPERATIVO</span>
            </div>

            <!-- Botón de Modo Diagnóstico / Tema -->
            <button onclick="toggleTheme()" type="button" class="p-2 border border-outline-variant bg-surface-container hover:border-primary-container text-on-surface-variant hover:text-primary-container transition-colors cursor-pointer" title="Alternar Modo Claro / Oscuro">
                <span class="material-symbols-outlined text-base block theme-toggle-icon">contrast</span>
            </button>

            <!-- Acceso a Portal Privado -->
            @auth
                <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 px-4 py-1.5 bg-primary-container text-noir-black font-label-md text-xs font-bold uppercase tracking-wider hover:bg-white hover:text-noir-black transition-colors amber-glow">
                    <span class="material-symbols-outlined text-sm">dashboard</span>
                    <span>Mi Workbench</span>
                </a>
            @else
                <a href="{{ url('/login') }}" class="flex items-center gap-2 px-4 py-1.5 bg-noir-blue border border-outline-variant text-white font-label-md text-xs uppercase tracking-wider hover:border-primary-container hover:text-primary-container transition-colors">
                    <span class="material-symbols-outlined text-sm">lock_open</span>
                    <span>Acceso Técnico / Cliente</span>
                </a>
            @endauth
        </div>
    </header>

    <!-- FRANJA DE TELEMETRÍA / AVISO INDUSTRIAL -->
    <div class="mt-16 bg-surface-container-low border-b border-outline-variant px-4 lg:px-8 py-1.5 flex items-center justify-between text-xs font-label-sm text-on-surface-variant">
        <div class="flex items-center gap-3 overflow-hidden text-ellipsis whitespace-nowrap">
            <span class="text-primary-container font-bold">[LAB-DIAG]</span>
            <span>Mesa de trabajo de hardware especializada // Diagnósticos sin costo garantizados en 24-48 hrs</span>
        </div>
        <div class="hidden md:flex items-center gap-4 text-primary font-mono text-[11px]">
            <span>TEL: (55) 8920-TECH</span>
            <span>POLÍTICA: 15% DCTO EN >5 EQUIPOS</span>
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- PIE DE PÁGINA PÚBLICO INDUSTRIAL -->
    <footer class="bg-surface-container-lowest border-t border-outline-variant text-on-surface-variant font-label-sm text-xs mt-16">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 py-10 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 bg-noir-blue border border-primary-container flex items-center justify-center font-bold text-primary-container text-xs">SF</div>
                    <span class="font-headline-md text-white font-bold text-sm tracking-wider uppercase">TecnoFix Solutions</span>
                </div>
                <p class="font-body-sm text-on-surface-variant text-xs leading-relaxed mb-3">
                    Plataforma integral SoporTec v4.2 para el diagnóstico, reparación, control de refacciones y gestión del ciclo de vida de hardware informático.
                </p>
                <span class="text-[10px] text-primary-container uppercase font-mono">Atmósfera Technical Noir // Cero Tolerancia al Error</span>
            </div>

            <div>
                <span class="text-white font-bold uppercase tracking-wider text-xs block mb-3 border-b border-outline-variant pb-1">Horarios de Taller</span>
                <ul class="space-y-1.5 text-xs font-mono">
                    <li class="flex justify-between"><span>Lunes a Viernes:</span> <span class="text-white">08:00 - 19:00</span></li>
                    <li class="flex justify-between"><span>Sábados:</span> <span class="text-white">09:00 - 15:00</span></li>
                    <li class="flex justify-between text-primary-container"><span>Recepción Emergencias:</span> <span>24/7 Portal</span></li>
                </ul>
            </div>

            <div>
                <span class="text-white font-bold uppercase tracking-wider text-xs block mb-3 border-b border-outline-variant pb-1">Políticas de Servicio</span>
                <ul class="space-y-1.5 text-xs">
                    <li>• Diagnóstico inicial sin costo ni compromiso.</li>
                    <li>• Garantía de 90 días naturales en toda reparación.</li>
                    <li>• Sustitución con piezas originales verificadas por almacén.</li>
                    <li>• Descuento fidelidad: 15% directo a partir del 6º equipo.</li>
                </ul>
            </div>

            <div>
                <span class="text-white font-bold uppercase tracking-wider text-xs block mb-3 border-b border-outline-variant pb-1">Ubicación de Laboratorio</span>
                <address class="not-italic space-y-1.5 text-xs font-mono">
                    <p class="text-white">Av. Tecnológica 1024, Edificio Alfa, Lab 3B</p>
                    <p>Mesa de Control: soporte@tecnofix.local</p>
                    <p class="text-primary-container">Línea Directa: +52 (55) 8920-8324</p>
                </address>
            </div>
        </div>

        <div class="border-t border-outline-variant/60 py-4 px-4 lg:px-8 text-center text-[11px] font-mono flex flex-col sm:flex-row items-center justify-between max-w-7xl mx-auto">
            <span>&copy; {{ date('Y') }} TecnoFix Solutions. Todos los derechos reservados. SoporTec Architecture v4.2</span>
            <span class="text-primary-container mt-2 sm:mt-0">[SEC-LEVEL: CERTIFIED NOIR BENCH]</span>
        </div>
    </footer>

    <!-- Script de alternancia de tema con persistencia -->
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcons();
        }

        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.theme-toggle-icon').forEach(icon => {
                icon.textContent = isDark ? 'contrast' : 'light_mode';
            });
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcons);
    </script>
    @stack('scripts')
</body>
</html>
