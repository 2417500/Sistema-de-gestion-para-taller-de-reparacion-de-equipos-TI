<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SoporTec') }} | Workbench</title>

        <!-- Prevención de destello de tema (Anti-Flicker Script) -->
        <script>
            (function() {
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme === 'light') {
                    document.documentElement.classList.remove('dark');
                } else {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,300..700;1,300..700&family=JetBrains+Mono:wght@400;500;600;700&family=Josefin+Sans:wght@600;700&family=Space+Grotesk:wght@500;600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Tailwind CSS v3 via CDN -->
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
                            "secondary": "#b9c6ea",
                            "secondary-container": "#14213d",
                            "error": "#ffb4ab",
                            "error-container": "#93000a"
                        },
                        fontFamily: {
                            "headline-xl": ["Space Grotesk", "sans-serif"],
                            "headline-lg": ["Space Grotesk", "sans-serif"],
                            "headline-md": ["Space Grotesk", "sans-serif"],
                            "body-lg": ["Hanken Grotesk", "sans-serif"],
                            "body-md": ["Hanken Grotesk", "sans-serif"],
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
            * {
                border-radius: 0px !important;
            }

            /* MODO CLARO / INVERSIÓN DIAGNÓSTICA */
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
            html:not(.dark) .bg-noir-blue * {
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
        </style>
    </head>
    <body class="bg-background text-on-surface antialiased font-mono film-grid min-h-screen selection:bg-primary-container selection:text-noir-black">
        <div class="min-h-screen flex flex-col">
            <!-- Barra superior -->
            <header class="h-16 bg-surface-container-lowest border-b border-outline-variant flex items-center justify-between px-6">
                <a href="/dashboard" class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-noir-blue border border-primary-container flex items-center justify-center font-bold text-primary-container font-mono">
                        SF
                    </div>
                    <span class="font-bold text-primary-container uppercase tracking-wider text-sm">TecnoFix Solutions | SoporTec v4.2</span>
                </a>

                <div class="flex items-center gap-4 text-xs font-mono">
                    <button onclick="toggleTheme()" type="button" class="p-1.5 border border-outline-variant bg-surface-container hover:border-primary-container text-on-surface-variant hover:text-primary-container transition-colors cursor-pointer" title="Alternar Modo Claro / Oscuro">
                        <span class="material-symbols-outlined text-sm block theme-toggle-icon">contrast</span>
                    </button>
                    <span class="text-on-surface-variant">{{ Auth::user()?->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-3 py-1 border border-outline-variant hover:border-error hover:text-error transition-colors uppercase text-[11px]">
                            Salir
                        </button>
                    </form>
                </div>
            </header>

            <!-- Encabezado de página opcional -->
            @isset($header)
                <div class="bg-surface-container-low border-b border-outline-variant px-6 py-3">
                    {{ $header }}
                </div>
            @endisset

            <!-- Contenido del Componente (sin padding p-6 para ajustar sidebars a pantalla completa) -->
            <main class="flex-grow">
                {{ $slot }}
            </main>
        </div>

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
    </body>
</html>