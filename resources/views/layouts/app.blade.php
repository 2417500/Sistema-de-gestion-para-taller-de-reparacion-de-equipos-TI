<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TecnoFix Solutions') }}</title>

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
        h1, h2, h3, .font-titulos { 
            font-family: 'Amaranth', sans-serif !important; 
        }
        h4, h5, h6, th, label, .font-subtitulos { 
            font-family: 'Karla', sans-serif !important; 
        }
        body, p, input, button, span, a, td, textarea, select, div { 
            font-family: 'Jost', sans-serif !important; 
        }

        /* ELIMINACIÓN DE PALABRAS DE ICONOS */
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

        /* PALETA DE VARIABLES - MODO OSCURO (TONALIDAD PROFUNDA OFICIAL) */
        :root {
            --bg-main: #1d2e26;
            --bg-sidebar: #16241d;
            --bg-card: #24382e;
            --bg-box: #18261f;
            --border-color: #588157;
            --text-title: #ffffff;
            --text-body: #dad7cd;
            --text-muted: #a3b18a;
            --accent-bg: #588157;
            --btn-bg: #a3b18a;
            --btn-text: #1d2e26;
            --table-header: #16241d;
        }

        /* PALETA DE VARIABLES - MODO CLARO (ALTO CONTRASTE) */
        html.light-mode {
            --bg-main: #dad7cd;
            --bg-sidebar: #a3b18a;
            --bg-card: #ffffff;
            --bg-box: #eef2e6;
            --border-color: #588157;
            --text-title: #1e3328;
            --text-body: #2c4236;
            --text-muted: #416350;
            --accent-bg: #588157;
            --btn-bg: #344e41;
            --btn-text: #ffffff;
            --table-header: #eef2e6;
        }

        body {
            background-color: var(--bg-main) !important;
            color: var(--text-body) !important;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        aside, .admin-sidebar {
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
        html.light-mode .text-white,
        html.light-mode .text-gray-100,
        html.light-mode .text-gray-200,
        html.light-mode .text-gray-300,
        html.light-mode .text-zinc-100,
        html.light-mode .text-zinc-200,
        html.light-mode .text-zinc-300,
        html.light-mode .text-\[\#dad7cd\],
        html.light-mode .text-\[\#e5e5e5\],
        html.light-mode h1,
        html.light-mode h2,
        html.light-mode h3,
        html.light-mode h4,
        html.light-mode td,
        html.light-mode th,
        html.light-mode label,
        html.light-mode p,
        html.light-mode pre,
        html.light-mode div {
            color: #1e3328 !important;
        }

        html.light-mode .text-gray-400,
        html.light-mode .text-gray-500,
        html.light-mode .text-zinc-400,
        html.light-mode .text-\[\#8a8e9b\],
        html.light-mode .text-\[\#a3b18a\] {
            color: #3a5a40 !important;
        }

        html.light-mode [class*="bg-emerald-9"],
        html.light-mode [class*="bg-green-9"] {
            background-color: #d8e8d4 !important;
            border-color: #3a5a40 !important;
            color: #143622 !important;
        }
        html.light-mode [class*="bg-emerald-9"] *,
        html.light-mode [class*="bg-green-9"] *,
        html.light-mode .text-emerald-300,
        html.light-mode .text-emerald-400 {
            color: #143622 !important;
        }

        html.light-mode .bg-emerald-600,
        html.light-mode .bg-emerald-600 *,
        html.light-mode .bg-emerald-700,
        html.light-mode .bg-emerald-700 *,
        html.light-mode .bg-\[\#588157\],
        html.light-mode .bg-\[\#588157\] *,
        html.light-mode .bg-\[\#344e41\],
        html.light-mode .bg-\[\#344e41\] *,
        html.light-mode .bg-\[\#24382c\],
        html.light-mode .bg-\[\#24382c\] * {
            color: #ffffff !important;
        }

        /* REEMPLAZO DE FONDOS NEGROS/GRISES OSCUROS POR PALETA OFICIAL */
        [class*="bg-[#0"], [class*="bg-[#1"], [class*="bg-[#20"], [class*="bg-[#21"], [class*="bg-[#22"], [class*="bg-[#23"],
        .bg-black, .bg-zinc-800, .bg-zinc-900, .bg-zinc-950,
        .bg-neutral-800, .bg-neutral-900, .bg-neutral-950,
        .bg-slate-800, .bg-slate-900, .bg-slate-950,
        .bg-gray-800, .bg-gray-900, .bg-gray-950,
        pre, code {
            background-color: var(--bg-box) !important;
            border-color: var(--border-color) !important;
            color: var(--text-title) !important;
        }

        html.light-mode [class*="bg-[#0"], html.light-mode [class*="bg-[#1"],
        html.light-mode .bg-black, html.light-mode .bg-zinc-800, html.light-mode .bg-zinc-900, html.light-mode .bg-zinc-950,
        html.light-mode .bg-neutral-800, html.light-mode .bg-neutral-900, html.light-mode .bg-neutral-950,
        html.light-mode .bg-slate-800, html.light-mode .bg-slate-900, html.light-mode .bg-slate-950,
        html.light-mode .bg-gray-800, html.light-mode .bg-gray-900, html.light-mode .bg-gray-950,
        html.light-mode pre, html.light-mode code {
            background-color: #eef2e6 !important;
            border-color: #588157 !important;
            color: #1e3328 !important;
        }

        /* REEMPLAZO DE AMARILLOS / NARANJAS */
        .badge-amber, .badge-warning,
        [class*="bg-[#ffb"], [class*="bg-[#fca"], [class*="bg-amber-"], [class*="bg-yellow-"], [class*="bg-orange-"] {
            background-color: #588157 !important;
            color: #ffffff !important;
            border-color: #a3b18a !important;
            font-weight: 700 !important;
        }

        [class*="text-[#ffb"], [class*="text-[#fca"], [class*="text-amber-"], [class*="text-yellow-"], [class*="text-orange-"] {
            color: #a3b18a !important;
        }
        html.light-mode [class*="text-[#ffb"], html.light-mode [class*="text-amber-"], html.light-mode [class*="text-yellow-"] {
            color: #3a5a40 !important;
        }

        .badge-green, .badge-success {
            background-color: #059669 !important;
            color: #ffffff !important;
        }

        .badge-info {
            background-color: #0284c7 !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body class="min-h-screen antialiased">

    {{ $slot ?? '' }}
    @yield('content')

    <!-- SCRIPT DE INTERACCIÓN MODO CLARO / OSCURO -->
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