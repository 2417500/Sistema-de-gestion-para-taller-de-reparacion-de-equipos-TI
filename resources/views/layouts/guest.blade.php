<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SoporTec') }} | Acceso a Terminal</title>

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

        <!-- Tailwind CSS v3 via CDN y Vite -->
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
                            "surface-container-lowest": "#0e0e0e",
                            "surface-container-low": "#1b1b1b",
                            "surface-container": "#1f1f1f",
                            "surface-container-high": "#2a2a2a",
                            "on-surface": "#e2e2e2",
                            "on-surface-variant": "#d9c3ad",
                            "outline-variant": "rgba(229, 229, 229, 0.12)",
                            "primary-container": "#fca311",
                            "error": "#ffb4ab",
                            "error-container": "#93000a"
                        },
                        fontFamily: {
                            "headline-md": ["Space Grotesk", "sans-serif"],
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
    <body class="bg-background text-on-surface antialiased font-mono film-grid min-h-screen flex flex-col justify-center items-center p-4 selection:bg-primary-container selection:text-noir-black relative">
        <!-- Botón Flotante de Tema en Esquina Superior Derecha -->
        <div class="absolute top-4 right-4">
            <button onclick="toggleTheme()" type="button" class="p-2 border border-outline-variant bg-surface-container hover:border-primary-container text-on-surface-variant hover:text-primary-container transition-colors cursor-pointer" title="Alternar Modo Claro / Oscuro">
                <span class="material-symbols-outlined text-base block theme-toggle-icon">contrast</span>
            </button>
        </div>

        <div class="w-full max-w-md">
            <!-- Emblema de Cabecera -->
            <div class="text-center mb-6">
                <a href="/" class="inline-flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-noir-blue border border-primary-container flex items-center justify-center font-josefin font-bold text-primary-container text-lg group-hover:bg-primary-container group-hover:text-noir-black transition-colors">
                        SF
                    </div>
                    <div class="text-left">
                        <span class="text-headline-md font-bold tracking-wider text-primary-container uppercase block text-base">TecnoFix Solutions</span>
                        <span class="text-[9px] font-mono uppercase bg-surface-container-high text-on-surface-variant px-1.5 py-0.5 border border-outline-variant">SoporTec v4.2 // Terminal</span>
                    </div>
                </a>
            </div>

            <!-- Contenedor del Formulario Noir -->
            <div class="noir-card bg-surface-container-lowest p-6 sm:p-8 amber-line">
                {{ $slot }}
            </div>

            <!-- Telemetría al pie -->
            <div class="mt-6 text-center text-[10px] font-mono text-on-surface-variant/60">
                <span>[SEC-ENCRYPT: TLS 1.3] // BANCO DE CONTROL INDUSTRIAL</span>
            </div>
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
