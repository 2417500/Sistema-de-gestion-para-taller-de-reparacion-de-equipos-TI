<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecnoFix Solutions | SoporTec v4.2</title>

    <!-- FUENTES OFICIALES (TITULOS: Amaranth | SUBTITULOS: Karla | TEXTO: Jost) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amaranth:ital,wght@0,400;0,700;1,400;1,700&family=Jost:ital,wght@0,100..900;1,100..900&family=Karla:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <!-- TAILWIND CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
        body, p, input, button, span, a, td, th, li, textarea, div { 
            font-family: 'Jost', sans-serif !important; 
        }

        /* MODO OSCURO (PREDETERMINADO - TONALIDAD PROFUNDA WORKBENCH) */
        :root {
            --bg-main: #1d2e26;
            --bg-sidebar: #16241d;
            --bg-card: #24382e;
            --bg-box: #16241d;
            --border-color: #588157;
            --text-title: #ffffff;
            --text-body: #dad7cd;
            --text-muted: #a3b18a;
            --text-highlight: #a3b18a;
            --bg-input: #16241d;
            --footer-bg: #16241d;
        }

        /* MODO CLARO (ALTO CONTRASTE CORREGIDO) */
        html.light-mode {
            --bg-main: #dad7cd;
            --bg-sidebar: #a3b18a;
            --bg-card: #ffffff;
            --bg-box: #eef2e6;
            --border-color: #588157;
            --text-title: #1e3328;
            --text-body: #2c4236;
            --text-muted: #416350;
            --text-highlight: #b45309;
            --bg-input: #ffffff;
            --footer-bg: #a3b18a;
        }

        body {
            background-color: var(--bg-main) !important;
            color: var(--text-body) !important;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dynamic-sidebar {
            background-color: var(--bg-sidebar) !important;
            border-color: var(--border-color) !important;
        }

        .dynamic-card {
            background-color: var(--bg-card) !important;
            border-color: var(--border-color) !important;
            color: var(--text-body) !important;
        }

        .dynamic-box {
            background-color: var(--bg-box) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-title) !important;
        }

        .dynamic-title {
            color: var(--text-title) !important;
        }

        .dynamic-muted {
            color: var(--text-muted) !important;
        }

        .dynamic-highlight {
            color: var(--text-highlight) !important;
        }

        .dynamic-footer {
            background-color: var(--footer-bg) !important;
            border-color: var(--border-color) !important;
        }
    </style>
</head>
<body class="min-h-screen pl-16 flex flex-col justify-between">

    <!-- NAVBAR VERTICAL INTERACTIVO (FIJO A LA IZQUIERDA) -->
    <aside class="group dynamic-sidebar fixed left-0 top-0 h-full w-16 hover:w-64 border-r transition-all duration-300 ease-in-out z-50 flex flex-col justify-between p-3 overflow-hidden shadow-2xl">
        
        <div class="space-y-6">
            <!-- LOGO/MARCA -->
            <div class="flex items-center gap-3 pb-3 border-b border-[#588157]">
                <div class="w-10 h-10 min-w-[2.5rem] bg-[#588157] text-[#dad7cd] font-bold flex items-center justify-center rounded text-sm shadow">
                    SF
                </div>
                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap overflow-hidden">
                    <h1 class="text-sm font-bold dynamic-title leading-none font-titulos">TECNOFIX</h1>
                    <span class="text-[10px] dynamic-title font-subtitulos uppercase">SOPORTEC V4.2</span>
                </div>
            </div>

            <!-- MENU NAVEGACIÓN SVG CON TEXTOS -->
            <nav class="space-y-2">
                <!-- TALLER -->
                <a href="#taller" class="flex items-center gap-4 px-2.5 py-3 rounded text-xs dynamic-title hover:bg-[#588157] hover:text-white transition-colors">
                    <svg class="w-5 h-5 min-w-[1.25rem] fill-current" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.4-2.4c.4-.4.4-1 0-1.3z"/></svg>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap font-subtitulos font-bold uppercase text-[11px]">TALLER</span>
                </a>

                <!-- SEGUIMIENTO -->
                <a href="#seguimiento" class="flex items-center gap-4 px-2.5 py-3 rounded text-xs dynamic-title hover:bg-[#588157] hover:text-white transition-colors">
                    <svg class="w-5 h-5 min-w-[1.25rem] fill-current" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap font-subtitulos font-bold uppercase text-[11px]">SEGUIMIENTO</span>
                </a>

                <!-- HORARIOS -->
                <a href="#horarios" class="flex items-center gap-4 px-2.5 py-3 rounded text-xs dynamic-title hover:bg-[#588157] hover:text-white transition-colors">
                    <svg class="w-5 h-5 min-w-[1.25rem] fill-current" viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap font-subtitulos font-bold uppercase text-[11px]">HORARIOS</span>
                </a>

                <!-- POLÍTICAS -->
                <a href="#politicas" class="flex items-center gap-4 px-2.5 py-3 rounded text-xs dynamic-title hover:bg-[#588157] hover:text-white transition-colors">
                    <svg class="w-5 h-5 min-w-[1.25rem] fill-current" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap font-subtitulos font-bold uppercase text-[11px]">POLÍTICAS</span>
                </a>

                <!-- UBICACIÓN -->
                <a href="#ubicacion" class="flex items-center gap-4 px-2.5 py-3 rounded text-xs dynamic-title hover:bg-[#588157] hover:text-white transition-colors">
                    <svg class="w-5 h-5 min-w-[1.25rem] fill-current" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap font-subtitulos font-bold uppercase text-[11px]">UBICACIÓN</span>
                </a>
            </nav>
        </div>

        <!-- CONTROLES BASE -->
        <div class="space-y-3 pt-3 border-t border-[#588157]">
            <!-- TEMA CLARO / OSCURO -->
            <button onclick="toggleTheme()" class="w-full flex items-center gap-4 px-2.5 py-2.5 rounded text-xs dynamic-title hover:bg-[#588157] hover:text-white transition-colors">
                <svg class="w-5 h-5 min-w-[1.25rem] fill-current" viewBox="0 0 24 24"><path d="M12 2c5.52 0 10 4.48 10 10s-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2zm0 18c4.42 0 8-3.58 8-8s-3.58-8-8-8v16z"/></svg>
                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap font-subtitulos font-bold uppercase text-[10px]">TEMA CLARO / OSCURO</span>
            </button>

            <!-- ACCESO SISTEMA -->
            @auth
                <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-4 px-2.5 py-3 rounded text-xs font-bold bg-[#a3b18a] text-[#344e41] hover:bg-[#dad7cd] transition-colors uppercase">
                    <svg class="w-5 h-5 min-w-[1.25rem] fill-current" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z"/></svg>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap font-bold uppercase text-[10px]">IR A MI WORKBENCH</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full flex items-center gap-4 px-2.5 py-3 rounded text-xs font-bold bg-[#a3b18a] text-[#344e41] hover:bg-[#dad7cd] transition-colors uppercase">
                    <svg class="w-5 h-5 min-w-[1.25rem] fill-current" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap font-bold uppercase text-[10px]">ACCESO SISTEMA</span>
                </a>
            @endauth
        </div>
    </aside>

    <!-- CONTENEDOR PRINCIPAL AUTOCENTRADO CON MAX-WIDTH -->
    <main class="w-full max-w-7xl mx-auto p-6 md:p-10 space-y-10">

        <!-- HERO SECTION NOIR WORKBENCH -->
        <div class="relative dynamic-card p-8 md:p-12 overflow-hidden border rounded shadow-xl">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-[#588157] font-mono text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-[#588157] animate-ping rounded-full"></span>
                    <span class="font-bold tracking-widest uppercase dynamic-title font-subtitulos">TECNOFIX PUBLIC WORKBENCH</span>
                </div>
            </div>

            <div class="max-w-3xl space-y-6 relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#24382c] border border-[#588157] rounded">
                    <span class="w-2 h-2 bg-[#a3b18a] animate-ping rounded-full"></span>
                    <span class="font-bold tracking-wider uppercase text-xs font-subtitulos text-[#a3b18a]">
                        AVISO PROTOCOLAR: DIAGNÓSTICO GRATUITO $0
                    </span>
                </div>

                <h1 class="font-titulos font-bold tracking-tight dynamic-title uppercase text-3xl md:text-4xl leading-tight">
                    Ingeniería Diagnóstica de Alta Precisión para Hardware Enterprise y Crítico.
                </h1>

                <p class="text-base leading-relaxed max-w-2xl">
                    TecnoFix Solutions (SoporTec v4.2) opera con tolerancia cero al fallo. Análisis de esquemáticos a nivel microcomponente, trazabilidad de circuito cerrado y auditoría transparente para servidores, estaciones portátiles y terminales empresariales.
                </p>

                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <a href="#seguimiento" class="px-6 py-3 bg-[#a3b18a] text-[#344e41] font-bold text-xs uppercase tracking-wider rounded hover:bg-[#588157] hover:text-white transition-all flex items-center gap-2 shadow">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        <span>Seguimiento de Ticket ($0 Diagnóstico)</span>
                    </a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-[#588157] text-white font-bold text-xs uppercase tracking-wider rounded border border-[#588157] hover:bg-[#a3b18a] hover:text-[#344e41] transition-all flex items-center gap-2">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z"/></svg>
                            <span>Ir a Mi Workbench (Panel)</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-3 bg-[#588157] text-white font-bold text-xs uppercase tracking-wider rounded border border-[#588157] hover:bg-[#a3b18a] hover:text-[#344e41] transition-all flex items-center gap-2">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                            <span>Acceso al Workbench Técnico</span>
                        </a>
                    @endauth
                </div>
            </div>

            <div class="absolute right-0 bottom-0 opacity-20 pointer-events-none select-none text-right pr-6 pb-4 hidden lg:block font-mono text-xs dynamic-muted">
                <div>// ARCHITECTURE: NOIR-BENCH-4.2</div>
                <div>// TELEMETRY: 24-48 HRS LATENCY</div>
                <div>// STATUS: ALL SYSTEMS NORMAL</div>
            </div>
        </div>

        <!-- SECCIÓN CONSULTA PÚBLICA DE TELEMETRÍA DE REPARACIÓN -->
        <div id="seguimiento" class="dynamic-card p-6 border-l-4 border-[#588157] border rounded shadow-lg">
            <div class="flex items-center gap-3 mb-4">
                <svg class="w-8 h-8 fill-current text-[#588157]" viewBox="0 0 24 24"><path d="M3 5v4h2V5h4V3H5c-1.1 0-2 .9-2 2zm2 10H3v4c0 1.1.9 2 2 2h4v-2H5v-4zm14 4h-4v2h4c1.1 0 2-.9 2-2v-4h-2v4zm0-14h-4V3h4c1.1 0 2 .9 2 2v4h-2V5zM7 7h10v10H7V7zm2 2v6h6V9H9z"/></svg>
                <div>
                    <h2 class="font-titulos font-bold dynamic-title uppercase text-xl">Consulta Pública de Telemetría de Reparación</h2>
                    <p class="text-xs font-subtitulos opacity-90">Ingrese el código de servicio impreso en su comprobante o el número de serie de su equipo.</p>
                </div>
            </div>

            <form action="{{ url('/seguimiento') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <svg class="w-4 h-4 fill-current absolute left-3 top-3.5 dynamic-title opacity-60" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                    <input type="text" name="codigo" value="{{ request('codigo', $codigo ?? '') }}" placeholder="EJ: ST-2026-0001 O NÚMERO DE SERIE (SN-LNV-XXXX)..." required
                           class="w-full pl-10 pr-4 py-2.5 border border-[#588157] text-sm dynamic-title placeholder-opacity-60 focus:border-[#588157] outline-none uppercase font-mono rounded" style="background-color: var(--bg-input);">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-[#a3b18a] text-[#344e41] font-bold text-xs uppercase tracking-wider hover:bg-[#588157] hover:text-white transition-colors rounded flex items-center justify-center gap-2">
                    <span>RASTREAR ESTADO</span>
                </button>
            </form>

            @if(session('success'))
                <div class="mt-4 p-4 bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 font-mono text-xs rounded flex items-center gap-2">
                    <svg class="w-4 h-4 fill-current text-emerald-300" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(isset($orden) && $orden)
                <div class="mt-6 p-5 border border-[#588157] rounded space-y-4 dynamic-box">
                    <div class="flex items-center justify-between border-b border-[#588157] pb-3">
                        <div>
                            <span class="font-subtitulos text-xs opacity-80 uppercase block">ORDEN DE SERVICIO</span>
                            <span class="font-titulos text-lg font-bold text-[#588157]">{{ $orden->codigo_servicio }}</span>
                        </div>
                        <span class="px-3 py-1 border border-[#588157] text-white bg-[#3a5a40] text-xs font-mono uppercase font-bold rounded">{{ str_replace('_', ' ', $orden->estado) }}</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-mono">
                        <div><span class="opacity-70">CLIENTE:</span> <span class="dynamic-title font-bold">{{ $orden->cliente_nombre ?? 'N/A' }}</span></div>
                        <div><span class="opacity-70">EQUIPO:</span> <span class="dynamic-title">{{ $orden->marca ?? '' }} {{ $orden->modelo ?? 'N/A' }}</span></div>
                        <div><span class="opacity-70">N° SERIE:</span> <span class="dynamic-title">{{ $orden->numero_serie ?? 'N/A' }}</span></div>
                    </div>

                    <div class="text-xs font-mono p-3 rounded border border-[#588157] dynamic-box">
                        <span class="opacity-70 block mb-1 font-subtitulos">FALLA / DETALLES REGISTRADOS:</span>
                        <p class="dynamic-title whitespace-pre-line">{{ $orden->falla_reportada }}</p>
                    </div>

                    <!-- ETAPAS DEL SERVICIO -->
                    <div class="pt-2">
                        <span class="text-[10px] font-subtitulos uppercase block mb-2 opacity-80">ETAPAS DEL SERVICIO:</span>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-center text-[11px] font-mono">
                            <div class="p-2 rounded border {{ in_array($orden->estado, ['Pendiente', 'Cotizacion_Enviada', 'En_Reparacion', 'Terminado', 'Entregado']) ? 'bg-[#588157] text-white font-bold' : 'opacity-40 border-[#588157]' }}">
                                1. RECEPCIÓN
                            </div>
                            <div class="p-2 rounded border {{ in_array($orden->estado, ['Cotizacion_Enviada', 'En_Reparacion', 'Terminado', 'Entregado']) ? 'bg-[#588157] text-white font-bold' : 'opacity-40 border-[#588157]' }}">
                                2. COTIZACIÓN
                            </div>
                            <div class="p-2 rounded border {{ in_array($orden->estado, ['En_Reparacion', 'Terminado', 'Entregado']) ? 'bg-[#588157] text-white font-bold' : 'opacity-40 border-[#588157]' }}">
                                3. REPARACIÓN
                            </div>
                            <div class="p-2 rounded border {{ in_array($orden->estado, ['Terminado', 'Entregado']) ? 'bg-emerald-600 text-white font-bold' : 'opacity-40 border-[#588157]' }}">
                                4. TERMINADO
                            </div>
                        </div>
                    </div>

                    <!-- RESPUESTA Y DESGLOSE -->
                    @if(in_array($orden->estado, ['Cotizacion_Enviada', 'COTIZADO', 'En_Reparacion', 'Terminado', 'Entregado']))
                        <div class="mt-4 p-4 border border-[#588157] rounded space-y-3 font-mono dynamic-box">
                            <div class="flex justify-between items-center border-b border-[#588157] pb-2">
                                <h4 class="text-[#588157] font-bold uppercase text-xs font-subtitulos">Presupuesto y Desglose de Diagnóstico</h4>
                                <span class="text-[10px] opacity-75">Revisión Técnica Completa</span>
                            </div>

                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="opacity-80">Costo Mano de Obra:</span>
                                    <span class="dynamic-title font-bold">${{ number_format($orden->costo_mano_obra ?? 0, 2) }} MXN</span>
                                </div>

                                @if(isset($refaccionesCotizadas) && count($refaccionesCotizadas) > 0)
                                    <div class="pt-2 border-t border-[#588157]">
                                        <span class="opacity-80 block mb-1">Refacciones Requeridas:</span>
                                        <ul class="space-y-1 pl-2">
                                            @foreach($refaccionesCotizadas as $ref)
                                                <li class="flex justify-between">
                                                    <span>{{ $ref->nombre }} (x{{ $ref->cantidad }})</span>
                                                    <span class="font-bold dynamic-title">${{ number_format($ref->precio_unitario * $ref->cantidad, 2) }} MXN</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @elseif(($orden->costo_refacciones ?? 0) > 0)
                                    <div class="flex justify-between">
                                        <span class="opacity-80">Costo Refacciones / Insumos:</span>
                                        <span class="dynamic-title font-bold">${{ number_format($orden->costo_refacciones, 2) }} MXN</span>
                                    </div>
                                @endif

                                <div class="pt-2 border-t border-[#588157] flex justify-between text-sm">
                                    <span class="dynamic-title font-bold">TOTAL ESTIMADO:</span>
                                    <span class="text-[#588157] font-bold">${{ number_format($orden->costo_total ?? 0, 2) }} MXN</span>
                                </div>
                                <div class="flex justify-between text-emerald-600 font-bold text-xs">
                                    <span>ANTICIPO MÍNIMO REQUERIDO (50%):</span>
                                    <span>${{ number_format(($orden->costo_total ?? 0) * 0.50, 2) }} MXN</span>
                                </div>
                            </div>

                            @if(in_array($orden->estado, ['Cotizacion_Enviada', 'COTIZADO']))
                                <form action="{{ route('cotizacion.responder') }}" method="POST" class="space-y-3 pt-3 border-t border-[#588157]">
                                    @csrf
                                    <input type="hidden" name="orden_id" value="{{ $orden->id }}">
                                    <textarea name="nota_cliente" placeholder="Escribe un comentario o solicitud de ajuste si lo requieres..." class="w-full border border-[#588157] dynamic-title p-2 text-xs rounded focus:border-[#588157] outline-none" style="background-color: var(--bg-input);"></textarea>
                                    
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <button type="submit" name="accion" value="aceptar" class="flex-1 py-2.5 bg-emerald-600 text-white font-bold rounded hover:bg-emerald-500 transition-colors uppercase text-xs">
                                            ✓ Aceptar Cotización
                                        </button>
                                        <button type="submit" name="accion" value="ajustar" class="flex-1 py-2.5 bg-amber-600 text-white font-bold rounded hover:bg-amber-500 transition-colors uppercase text-xs">
                                            💬 Solicitar Ajuste
                                        </button>
                                        <button type="submit" name="accion" value="rechazar" class="flex-1 py-2.5 bg-rose-600 text-white font-bold rounded hover:bg-rose-500 transition-colors uppercase text-xs">
                                            ✕ Rechazar
                                        </button>
                                    </div>
                                </form>
                            @elseif($orden->estado === 'En_Reparacion')
                                <div class="p-3 bg-emerald-600 text-white rounded text-xs text-center font-bold">
                                    ✓ COTIZACIÓN ACEPTADA - EL TÉCNICO SE ENCUENTRA TRABAJANDO EN TU EQUIPO.
                                </div>
                            @endif

                        </div>
                    @endif
                </div>
            @elseif(request('codigo'))
                <div class="mt-6 p-4 bg-rose-900/20 border border-rose-500 text-rose-600 text-xs font-mono rounded">
                    No se encontró ninguna orden de servicio con el código o número de serie provisto [{{ request('codigo') }}]. Verifique el comprobante impreso.
                </div>
            @endif
        </div>

        <!-- PROTOCOLO DE INTERVENCIÓN TÉCNICA -->
        <div id="taller" class="space-y-6">
            <div class="flex items-center justify-between border-b border-[#588157] pb-3">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 fill-current text-[#588157]" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.4-2.4c.4-.4.4-1 0-1.3z"/></svg>
                    <h2 class="font-titulos font-bold dynamic-title uppercase text-xl">Protocolo de Intervención Técnica</h2>
                </div>
                <span class="text-xs font-mono opacity-80">SOPORTEC v4.2 SPEC</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="dynamic-card p-5 border-t-4 border-[#588157] rounded shadow">
                    <div class="text-[10px] uppercase font-mono mb-2 font-bold dynamic-muted">FASE 01</div>
                    <h3 class="font-titulos dynamic-title font-bold text-sm uppercase mb-2">Ingreso & Registro</h3>
                    <p class="text-xs leading-normal">
                        Recepción formal, inspección óptica de chasis, toma de evidencias fotográficas y generación de ticket térmico.
                    </p>
                </div>

                <div class="dynamic-card p-5 border-t-4 border-[#588157] rounded shadow">
                    <div class="text-[10px] uppercase font-mono mb-2 font-bold dynamic-muted">FASE 02</div>
                    <h3 class="font-titulos dynamic-title font-bold text-sm uppercase mb-2">Diagnóstico ($0)</h3>
                    <p class="text-xs leading-normal">
                        Revisión eléctrica, prueba de componentes y análisis de circuitos sin costo ni compromiso alguno.
                    </p>
                </div>

                <div class="dynamic-card p-5 border-t-4 border-[#588157] rounded shadow">
                    <div class="text-[10px] uppercase font-mono mb-2 font-bold dynamic-muted">FASE 03</div>
                    <h3 class="font-titulos dynamic-title font-bold text-sm uppercase mb-2">Cotización</h3>
                    <p class="text-xs leading-normal">
                        Presupuesto digital transparente desglosando mano de obra y refacciones con aprobación en línea.
                    </p>
                </div>

                <div class="dynamic-card p-5 border-t-4 border-[#588157] rounded shadow">
                    <div class="text-[10px] uppercase font-mono mb-2 font-bold dynamic-muted">FASE 04</div>
                    <h3 class="font-titulos dynamic-title font-bold text-sm uppercase mb-2">Reparación & QA</h3>
                    <p class="text-xs leading-normal">
                        Sustitución con refacciones originales, soldadura de micro-precisión y pruebas de estrés térmico continuo.
                    </p>
                </div>

                <div class="dynamic-card p-5 border-t-4 border-[#344e41] rounded shadow">
                    <div class="text-[10px] uppercase font-mono mb-2 font-bold dynamic-muted">FASE 05</div>
                    <h3 class="font-titulos dynamic-title font-bold text-sm uppercase mb-2">Entrega & Descuento</h3>
                    <p class="text-xs leading-normal">
                        Emisión de factura y póliza de garantía de 90 días. Descuento del 15% directo a partir del 6º equipo.
                    </p>
                </div>
            </div>
        </div>

        <!-- FIDELIDAD Y HORARIOS DETALLADOS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- TARJETA DESCUENTO FIDELIDAD -->
            <div class="dynamic-card p-6 border-l-4 border-[#588157] rounded shadow-md">
                <svg class="w-7 h-7 fill-current text-[#588157] mb-2" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                <h3 class="font-titulos font-bold dynamic-title uppercase text-base mb-2">Descuento de Fidelidad: 15% al Superar 5 Equipos</h3>
                <p class="text-xs leading-relaxed mb-4">
                    El sistema detecta automáticamente el historial de su cuenta. Una vez que registra y repara más de 5 equipos con nosotros, todas las órdenes subsecuentes reciben un <strong class="dynamic-title font-bold">descuento automático del 15%</strong> reflejado en su ticket de entrega.
                </p>
                <!-- CAJA DE REGLA CORREGIDA PARA ALTO CONTRASTE -->
                <div class="dynamic-box p-3 font-mono text-xs rounded font-bold">
                    REGLA: EQUIPOS_REPARADOS_COUNT > 5 → APLICA_DESCUENTO = TRUE (-15%)
                </div>
            </div>

            <!-- TARJETA HORARIOS Y TIEMPOS DE RESPUESTA -->
            <div class="dynamic-card p-6 border-l-4 border-[#588157] rounded shadow-md">
                <svg class="w-7 h-7 fill-current text-[#588157] mb-2" viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                <h3 class="font-titulos font-bold dynamic-title uppercase text-base mb-2">Horarios & Tiempos de Respuesta</h3>
                <ul class="text-xs font-mono space-y-2.5 mb-4">
                    <li class="flex justify-between border-b border-[#588157]/40 pb-1.5">
                        <span class="opacity-90">Lunes a Viernes:</span> 
                        <span class="dynamic-title font-bold">08:00 - 19:00 hrs</span>
                    </li>
                    <li class="flex justify-between border-b border-[#588157]/40 pb-1.5">
                        <span class="opacity-90">Sábados:</span> 
                        <span class="dynamic-title font-bold">09:00 - 15:00 hrs</span>
                    </li>
                    <li class="flex justify-between pt-1 font-bold">
                        <span class="dynamic-muted">Tiempo de Diagnóstico:</span> 
                        <span class="dynamic-title">24 a 48 Horas Garantizado</span>
                    </li>
                </ul>
                <div class="dynamic-box p-2.5 font-mono text-xs rounded font-bold">
                    UBICACIÓN: LAB CENTRAL - AV. TECNOLÓGICA 1024
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER INFERIOR CENTRADO -->
    <footer class="w-full dynamic-footer border-t pt-10 pb-6 px-6 md:px-12 text-xs mt-10">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 pb-8 border-b border-[#588157]">
            
            <!-- COLUMNA 1: TECNOFIX SOLUTIONS -->
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-[#588157] text-white font-bold flex items-center justify-center rounded text-xs">
                        SF
                    </div>
                    <span class="font-bold dynamic-title font-titulos text-sm">TECNOFIX SOLUTIONS</span>
                </div>
                <p class="dynamic-title text-[11px] leading-relaxed">
                    Plataforma integral SoporTec v4.2 para el diagnóstico, reparación, control de refacciones y gestión del ciclo de vida de hardware informático.
                </p>
                <div class="text-[10px] font-mono font-bold dynamic-muted">
                    ATMÓSFERA TECHNICAL NOIR // CERO TOLERANCIA AL ERROR
                </div>
            </div>

            <!-- COLUMNA 2: HORARIOS DE TALLER -->
            <div id="horarios" class="space-y-3">
                <h4 class="font-bold dynamic-title uppercase font-subtitulos tracking-wider">HORARIOS DE TALLER</h4>
                <ul class="space-y-1.5 text-[11px]">
                    <li class="flex justify-between"><span class="dynamic-title">Lunes a Viernes:</span> <span class="font-mono font-bold dynamic-title">08:00 - 19:00</span></li>
                    <li class="flex justify-between"><span class="dynamic-title">Sábados:</span> <span class="font-mono font-bold dynamic-title">09:00 - 15:00</span></li>
                    <li class="flex justify-between pt-1 border-t border-[#588157]/50"><span class="font-bold dynamic-muted">Recepción Emergencias:</span> <span class="font-mono font-bold dynamic-title">24/7 Portal</span></li>
                </ul>
            </div>

            <!-- COLUMNA 3: POLÍTICAS DE SERVICIO -->
            <div id="politicas" class="space-y-3">
                <h4 class="font-bold dynamic-title uppercase font-subtitulos tracking-wider">POLÍTICAS DE SERVICIO</h4>
                <ul class="space-y-1.5 text-[11px] dynamic-title list-disc list-inside">
                    <li>Diagnóstico inicial sin costo ni compromiso.</li>
                    <li>Garantía de 90 días naturales en toda reparación.</li>
                    <li>Sustitución con piezas originales verificadas por almacén.</li>
                    <li>Descuento fidelidad: 15% directo a partir del 6° equipo.</li>
                </ul>
            </div>

            <!-- COLUMNA 4: UBICACIÓN DE LABORATORIO -->
            <div id="ubicacion" class="space-y-3">
                <h4 class="font-bold dynamic-title uppercase font-subtitulos tracking-wider">UBICACIÓN DE LABORATORIO</h4>
                <p class="dynamic-title text-[11px] leading-relaxed">
                    Av. Tecnológica 1024, Edificio Alfa, Lab 3B
                </p>
                <p class="dynamic-title text-[11px]">
                    Mesa de Control: <span class="font-bold">soporte@tecnofix.local</span>
                </p>
                <p class="dynamic-title text-[11px]">
                    Línea Directa: <span class="font-bold">+52 (55) 8920-8324</span>
                </p>
            </div>

        </div>

        <!-- BARRA DERECHOS Y SEGURIDAD -->
        <div class="max-w-7xl mx-auto pt-4 flex flex-col sm:flex-row justify-between items-center text-[10px] font-mono dynamic-title">
            <div>
                © 2026 TecnoFix Solutions. Todos los derechos reservados. SoporTec Architecture v4.2
            </div>
            <div class="font-bold uppercase">
                [SEC-LEVEL: CERTIFIED NOIR BENCH]
            </div>
        </div>
    </footer>

    <!-- SCRIPT TEMA CLARO / OSCURO -->
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