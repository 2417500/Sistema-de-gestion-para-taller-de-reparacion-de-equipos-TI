<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'TecnoFix Noir — Precision Diagnostic Console')</title>

    <!-- Google Fonts: Space Grotesk & Geist / JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
    <!-- Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <script>
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            noir: {
                                canvas: "#000000",
                                base: "#080d18",
                                card: "#0e172a",
                                prussian: "#14213d",
                                border: "#1f2e4d",
                                amber: "#fca311",
                                alabaster: "#e5e5e5",
                                muted: "#7a889b"
                            }
                        },
                        fontFamily: {
                            sans: ["Geist", "sans-serif"],
                            display: ["Space Grotesk", "sans-serif"],
                            mono: ["JetBrains Mono", "Geist", "monospace"]
                        }
                    }
                }
            }
        </script>
        <style>
            body {
                font-family: 'Geist', sans-serif;
                background-color: #000000;
                color: #ffffff;
            }
            h1, h2, h3, .font-display {
                font-family: 'Space Grotesk', sans-serif;
            }
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 350, 'GRAD' 0, 'opsz' 20;
                display: inline-block;
                vertical-align: middle;
            }
        </style>
    @endif

    @stack('styles')
</head>
<body class="bg-black text-white min-h-screen flex antialiased selection:bg-[#fca311] selection:text-black">

    <!-- ========================================== -->
    <!-- TECHNICAL NOIR SIDEBAR (0px RADIUS)        -->
    <!-- ========================================== -->
    <aside class="fixed top-0 left-0 h-screen w-[280px] z-40 bg-black border-r border-[#14213d] flex flex-col justify-between py-5 px-4 select-none">
        <div class="flex flex-col space-y-6">
            <!-- Brand Header -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 px-2 border-b border-[#14213d] pb-4">
                <div class="w-8 h-8 bg-[#fca311] text-black flex items-center justify-center font-display font-bold text-sm tracking-tight">
                    TF
                </div>
                <div class="flex flex-col">
                    <span class="font-display font-bold text-sm text-white tracking-wider uppercase">TecnoFix Noir</span>
                    <span class="text-[10px] text-[#7a889b] font-display uppercase tracking-[0.08em]">Cleanroom Diagnostics</span>
                </div>
            </a>

            <!-- Navigation Items (0px sharp geometry) -->
            <nav class="flex flex-col space-y-1">
                <div class="px-2 pb-1 text-[10px] uppercase tracking-[0.08em] font-semibold font-display text-[#7a889b]">Estación de Trabajo</div>

                <a href="{{ route('dashboard', ['rol' => 'tecnico']) }}" class="w-full flex items-center gap-3 px-3 py-2.5 bg-[#0e172a] border-l-[3px] border-[#fca311] text-white font-display text-xs uppercase tracking-[0.05em]">
                    <span class="material-symbols-outlined text-[18px] text-[#fca311]">memory</span>
                    <span>Laboratorio / Bay 04</span>
                </a>
                <a href="{{ route('dashboard', ['rol' => 'recepcion']) }}" class="w-full flex items-center gap-3 px-3 py-2.5 text-[#7a889b] hover:text-white hover:bg-[#080d18] font-display text-xs uppercase tracking-[0.05em] transition-colors">
                    <span class="material-symbols-outlined text-[18px]">qr_code_scanner</span>
                    <span>Admisión Hardware</span>
                </a>
                <a href="{{ route('dashboard', ['rol' => 'almacen']) }}" class="w-full flex items-center gap-3 px-3 py-2.5 text-[#7a889b] hover:text-white hover:bg-[#080d18] font-display text-xs uppercase tracking-[0.05em] transition-colors">
                    <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                    <span>Inventario OEM / BGA</span>
                </a>
                <a href="{{ route('dashboard', ['rol' => 'admin']) }}" class="w-full flex items-center gap-3 px-3 py-2.5 text-[#7a889b] hover:text-white hover:bg-[#080d18] font-display text-xs uppercase tracking-[0.05em] transition-colors">
                    <span class="material-symbols-outlined text-[18px]">monitoring</span>
                    <span>Telemetría &amp; SLAs</span>
                </a>
            </nav>
        </div>

        <div class="pt-4 border-t border-[#14213d] space-y-3">
            <div class="flex items-center justify-between px-2 py-2 bg-[#080d18] border border-[#14213d]">
                <div>
                    <span class="text-xs font-display font-semibold text-white block uppercase">Ing. C. Mendoza</span>
                    <span class="text-[10px] font-mono text-[#fca311]">ESD SAFE • BAY #04</span>
                </div>
                <span class="w-2 h-2 bg-[#70e000]"></span>
            </div>
            <div class="flex items-center justify-between px-1 text-[10px] font-mono text-[#7a889b]">
                <span>MODO: NOIR INDUSTRIAL</span>
                <a href="{{ route('dashboard') }}" class="text-[#fca311] hover:underline uppercase">Modo Claro</a>
            </div>
        </div>
    </aside>

    <!-- ========================================== -->
    <!-- MAIN NOIR WORKSPACE                        -->
    <!-- ========================================== -->
    <div class="flex-1 flex flex-col min-w-0 pl-[280px]">
        <header class="sticky top-0 z-30 flex items-center justify-between w-full h-14 px-8 bg-[#080d18] border-b border-[#14213d]">
            <div class="flex items-center gap-3 flex-1 max-w-md">
                <input type="text" placeholder="BUSCAR SERIAL / IMEI / MAC / ORDEN..."
                    class="w-full px-3 py-1.5 bg-black border border-[#1f2e4d] text-xs font-mono text-white placeholder:text-[#49576e] focus:border-[#fca311] focus:outline-none" />
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="px-3 py-1.5 bg-[#14213d] border border-[#1f2e4d] hover:border-[#e5e5e5] text-white font-display text-xs uppercase tracking-wider transition-colors">
                    Vista Prussian Light
                </a>
                <button type="button" class="px-3 py-1.5 bg-[#fca311] hover:bg-white text-black font-display font-bold text-xs uppercase tracking-wider transition-colors">
                    + Diagnóstico
                </button>
            </div>
        </header>

        <main class="flex-1 p-8 space-y-6 max-w-7xl w-full mx-auto">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
