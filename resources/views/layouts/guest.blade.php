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
                    document.documentElement.classList.add('light-mode');
                } else {
                    document.documentElement.classList.add('dark');
                    document.documentElement.classList.remove('light-mode');
                }
            })();
        </script>

        <!-- Google Fonts Oficiales (Amaranth | Karla | Jost) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Amaranth:ital,wght@0,400;0,700;1,400;1,700&family=Jost:ital,wght@0,100..900;1,100..900&family=Karla:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
            h1, h2, h3, .font-titulos { 
                font-family: 'Amaranth', sans-serif !important; 
            }
            h4, h5, h6, .font-subtitulos { 
                font-family: 'Karla', sans-serif !important; 
            }
            body, p, input, button, span, a, td, th, label, select, option, textarea, div { 
                font-family: 'Jost', sans-serif !important; 
            }

            /* PALETA DE VARIABLES - MODO OSCURO (TONALIDAD PROFUNDA WORKBENCH) */
            :root {
                --bg-main: #1d2e26;
                --bg-card: #24382e;
                --bg-box: #16241d;
                --border-color: #588157;
                --text-title: #ffffff;
                --text-body: #dad7cd;
                --text-muted: #a3b18a;
                --bg-input: #16241d;
            }

            /* PALETA DE VARIABLES - MODO CLARO (ALTO CONTRASTE - INTACTO) */
            html:not(.dark), html.light-mode {
                color-scheme: light;
                --bg-main: #dad7cd;
                --bg-card: #ffffff;
                --bg-box: #eef2e6;
                --border-color: #588157;
                --text-title: #344e41;
                --text-body: #3a5a40;
                --text-muted: #588157;
                --bg-input: #ffffff;
            }

            body {
                background-color: var(--bg-main) !important;
                color: var(--text-body) !important;
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            .film-grid {
                background-size: 24px 24px;
                background-image: 
                    linear-gradient(to right, rgba(88, 129, 87, 0.15) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(88, 129, 87, 0.15) 1px, transparent 1px);
            }

            .noir-card {
                background-color: var(--bg-card) !important;
                border: 1px solid var(--border-color) !important;
                border-top: 3px solid #588157 !important;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            }

            .dynamic-title { 
                color: var(--text-title) !important; 
            }

            .dynamic-muted { 
                color: var(--text-muted) !important; 
            }

            input[type="text"], 
            input[type="email"], 
            input[type="password"],
            input[type="tel"],
            input[type="number"],
            select,
            option,
            textarea {
                background-color: var(--bg-input) !important;
                border: 1px solid var(--border-color) !important;
                color: var(--text-title) !important;
            }

            input::placeholder,
            textarea::placeholder {
                color: var(--text-muted) !important;
                opacity: 0.7;
            }

            input:focus,
            select:focus,
            textarea:focus {
                border-color: #a3b18a !important;
                box-shadow: 0 0 0 1px #588157 !important;
                outline: none !important;
            }

            /* CAJAS DE NOTIFICACIÓN / SIMULACIÓN SMS Y CÓDIGOS EN MODO OSCURO */
            html.dark .noir-card [class*="bg-emerald-"],
            html.dark .noir-card [class*="bg-amber-"],
            html.dark .noir-card [class*="bg-yellow-"],
            html.dark .noir-card [class*="bg-zinc-"],
            html.dark .noir-card [class*="bg-gray-"],
            html.dark .noir-card [class*="bg-slate-"],
            html.dark .noir-card [class*="bg-[#1"],
            html.dark .noir-card [class*="bg-[#2"],
            html.dark .noir-card [class*="bg-[#3"] {
                background-color: #16241d !important;
                border-color: #588157 !important;
                color: #dad7cd !important;
            }

            /* Botón de alternancia de tema adaptado al Modo Oscuro sin alterar Modo Claro */
            html.dark .theme-btn-guest {
                background-color: #24382e !important;
                border-color: #588157 !important;
                color: #dad7cd !important;
            }
            html.dark .theme-btn-guest:hover {
                background-color: #588157 !important;
                color: #ffffff !important;
            }

            html.dark .badge-terminal {
                background-color: #16241d !important;
                color: #a3b18a !important;
                border-color: #588157 !important;
            }
        </style>
    </head>
    <body class="antialiased film-grid min-h-screen flex flex-col justify-center items-center p-4 relative">
        
        <!-- Botón Flotante de Tema en Esquina Superior Derecha -->
        <div class="absolute top-4 right-4 z-50">
            <button onclick="toggleTheme()" type="button" class="theme-btn-guest p-2.5 border border-[#588157] bg-[#3a5a40] hover:bg-[#588157] text-[#dad7cd] hover:text-white transition-colors cursor-pointer rounded shadow" title="Alternar Modo Claro / Oscuro">
                <svg class="w-5 h-5 fill-current theme-toggle-icon" viewBox="0 0 24 24">
                    <path d="M12 2c5.52 0 10 4.48 10 10s-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2zm0 18c4.42 0 8-3.58 8-8s-3.58-8-8-8v16z"/>
                </svg>
            </button>
        </div>

        <div class="w-full max-w-md">
            <!-- Emblema de Cabecera -->
            <div class="text-center mb-6">
                <a href="/" class="inline-flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-[#588157] text-[#dad7cd] font-bold flex items-center justify-center rounded text-sm shadow group-hover:bg-[#a3b18a] group-hover:text-[#344e41] transition-colors">
                        SF
                    </div>
                    <div class="text-left">
                        <span class="font-titulos font-bold tracking-wider dynamic-title uppercase block text-base leading-none">TecnoFix Solutions</span>
                        <span class="badge-terminal text-[9px] font-subtitulos uppercase bg-[#344e41] text-[#a3b18a] px-1.5 py-0.5 border border-[#588157] rounded inline-block mt-1">SoporTec v4.2 // Terminal</span>
                    </div>
                </a>
            </div>

            <!-- Contenedor del Formulario Noir -->
            <div class="noir-card p-6 sm:p-8 rounded">
                {{ $slot }}
            </div>

            <!-- Telemetría al pie -->
            <div class="mt-6 text-center text-[10px] font-mono dynamic-muted">
                <span>[SEC-ENCRYPT: TLS 1.3] // BANCO DE CONTROL INDUSTRIAL</span>
            </div>
        </div>

        <script>
            function toggleTheme() {
                const html = document.documentElement;
                const isDark = html.classList.toggle('dark');
                if (isDark) {
                    html.classList.remove('light-mode');
                    localStorage.setItem('theme', 'dark');
                } else {
                    html.classList.add('light-mode');
                    localStorage.setItem('theme', 'light');
                }
            }
        </script>
    </body>
</html>