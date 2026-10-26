<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'TecnoFix Solutions | Taller de Reparación Especializada de Equipos TI')</title>

    <!-- Google Fonts: Inter, Jost, Josefin Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Josefin+Sans:wght@500;600;700&family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback CDN para desarrollo sin Vite corriendo -->
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            brand: {
                                navy: '#14213D',
                                'navy-hover': '#0e172a',
                                amber: '#D97706',
                                'amber-light': '#FEF3C7',
                                'amber-border': '#FCD34D',
                                slate: '#0F172A',
                                muted: '#64748B',
                                border: '#E2E8F0',
                                canvas: '#F8FAFC',
                                card: '#FFFFFF'
                            }
                        },
                        fontFamily: {
                            sans: ['Inter', 'Jost', 'system-ui', 'sans-serif'],
                            display: ['Josefin Sans', 'Inter', 'sans-serif'],
                            mono: ['JetBrains Mono', 'monospace']
                        },
                        borderRadius: {
                            card: '8px',
                            btn: '6px'
                        }
                    }
                }
            }
        </script>
        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 350, 'GRAD' 0, 'opsz' 20;
                display: inline-block;
                vertical-align: middle;
            }
        </style>
    @endif

    @stack('styles')
</head>
<body class="bg-brand-canvas text-brand-slate font-sans antialiased selection:bg-brand-navy selection:text-white min-h-screen flex flex-col">

    <!-- ========================================== -->
    <!-- TOP PUBLIC NAVIGATION                      -->
    <!-- ========================================== -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-brand-border px-4 sm:px-8 py-3.5 transition-all">
        <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                <div class="w-8 h-8 rounded-lg bg-brand-navy flex items-center justify-center text-white shadow-sm">
                    <span class="material-symbols-outlined text-lg">precision_manufacturing</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-semibold tracking-tight text-brand-slate leading-tight group-hover:text-black">TecnoFix Solutions</span>
                    <span class="text-[11px] text-brand-muted font-medium leading-tight">Laboratorio TI Especializado</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-6">
                <a href="{{ url('/#servicios') }}" class="text-sm text-brand-muted hover:text-brand-slate font-medium transition-colors">Servicios</a>
                <a href="{{ url('/#horarios') }}" class="text-sm text-brand-muted hover:text-brand-slate font-medium transition-colors">Horarios</a>
                <a href="{{ url('/#taller') }}" class="text-sm text-brand-muted hover:text-brand-slate font-medium transition-colors">Ubicación</a>
                <a href="{{ url('/#politicas') }}" class="text-sm text-brand-muted hover:text-brand-slate font-medium transition-colors">Políticas</a>
            </nav>

            <!-- Action Cluster -->
            <div class="flex items-center gap-3">
                <a href="{{ url('/#rastreo') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-brand-slate bg-white border border-brand-border rounded-lg hover:border-slate-400 hover:bg-slate-50 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-sm text-brand-muted">search</span>
                    <span>Rastrear Orden</span>
                </a>
                <a href="{{ route('dashboard') }}" class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-brand-slate bg-slate-100 hover:bg-slate-200 rounded-lg transition-all">
                    <span class="material-symbols-outlined text-sm text-brand-navy">space_dashboard</span>
                    <span>Consola Operativa</span>
                </a>
                <button type="button" onclick="openAuthModal()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-medium text-white bg-brand-navy hover:bg-brand-navy-hover rounded-lg transition-all shadow-sm">
                    <span class="material-symbols-outlined text-sm">login</span>
                    <span>Iniciar Sesión</span>
                </button>
            </div>
        </div>
    </header>

    <!-- ========================================== -->
    <!-- MAIN PUBLIC CONTENT                        -->
    <!-- ========================================== -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- ========================================== -->
    <!-- FOOTER MINIMALISTA                         -->
    <!-- ========================================== -->
    <footer class="bg-white border-t border-brand-border text-brand-muted py-12 px-4 sm:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-10 text-xs">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 rounded bg-brand-navy text-white flex items-center justify-center">
                            <span class="material-symbols-outlined text-sm">precision_manufacturing</span>
                        </div>
                        <span class="text-sm font-semibold text-brand-slate">TecnoFix Solutions</span>
                    </div>
                    <p class="leading-relaxed text-brand-muted">
                        Laboratorio especializado en ingeniería electrónica y recuperación de hardware crítico desde 2012.
                    </p>
                </div>
                <div>
                    <div class="font-semibold text-brand-slate mb-3">Servicios</div>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/#servicios') }}" class="hover:text-brand-slate">Servidores &amp; Blades</a></li>
                        <li><a href="{{ url('/#servicios') }}" class="hover:text-brand-slate">Laptops Corporativas</a></li>
                        <li><a href="{{ url('/#servicios') }}" class="hover:text-brand-slate">Recuperación en Cleanroom</a></li>
                        <li><a href="{{ url('/#servicios') }}" class="hover:text-brand-slate">Mantenimiento Térmico</a></li>
                    </ul>
                </div>
                <div>
                    <div class="font-semibold text-brand-slate mb-3">Políticas</div>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/#politicas') }}" class="hover:text-brand-slate">Diagnóstico en 24h</a></li>
                        <li><a href="{{ url('/#politicas') }}" class="hover:text-brand-slate">Acuerdo de Confidencialidad</a></li>
                        <li><a href="{{ url('/#politicas') }}" class="hover:text-brand-slate">Garantía de 180 Días</a></li>
                        <li><a href="{{ url('/#horarios') }}" class="hover:text-brand-slate">Envíos e Ingreso</a></li>
                    </ul>
                </div>
                <div>
                    <div class="font-semibold text-brand-slate mb-3">Certificaciones</div>
                    <div class="grid grid-cols-2 gap-2 text-center">
                        <div class="border border-brand-border p-2 rounded bg-brand-canvas">
                            <span class="font-semibold text-brand-slate block font-mono text-[11px]">ISO 5</span>
                            <span class="text-[10px] text-brand-muted">Cleanroom</span>
                        </div>
                        <div class="border border-brand-border p-2 rounded bg-brand-canvas">
                            <span class="font-semibold text-brand-slate block font-mono text-[11px]">ESD S20.20</span>
                            <span class="text-[10px] text-brand-muted">Electrostatic</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-brand-border pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400">
                <div>&copy; {{ date('Y') }} TecnoFix Solutions. Todos los derechos reservados.</div>
                <div class="mt-2 sm:mt-0 font-mono">LATAM • ESTACIÓN ONLINE</div>
            </div>
        </div>
    </footer>

    <!-- ========================================== -->
    <!-- AUTH POPUP MODAL (Quick Access)            -->
    <!-- ========================================== -->
    <div id="authModalBackdrop" class="fixed inset-0 z-50 bg-brand-slate/40 backdrop-blur-xs hidden flex items-center justify-center p-4">
        <div class="bg-white border border-brand-border rounded-lg max-w-sm w-full shadow-lg p-5 relative">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-brand-border">
                <span class="text-sm font-semibold text-brand-slate flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base text-brand-navy">lock</span>
                    <span>Acceso Directo al Sistema</span>
                </span>
                <button type="button" onclick="closeAuthModal()" class="text-brand-muted hover:text-brand-slate">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
            <p class="text-xs text-brand-muted mb-3">
                Acceso rápido a los módulos de trabajo y consola según su rol:
            </p>
            <div class="space-y-1.5 mb-3">
                <a href="{{ route('dashboard', ['rol' => 'admin']) }}" class="w-full p-2.5 bg-brand-canvas border border-brand-border hover:border-slate-400 rounded-lg text-left text-xs font-medium flex justify-between items-center text-brand-slate transition-colors">
                    <span>Consola Administrador</span>
                    <span class="text-brand-muted font-mono text-[10px]">ROOT</span>
                </a>
                <a href="{{ route('dashboard', ['rol' => 'tecnico']) }}" class="w-full p-2.5 bg-brand-canvas border border-brand-border hover:border-slate-400 rounded-lg text-left text-xs font-medium flex justify-between items-center text-brand-slate transition-colors">
                    <span>Módulo de Técnico</span>
                    <span class="text-brand-muted font-mono text-[10px]">DIAG</span>
                </a>
                <a href="{{ route('dashboard', ['rol' => 'recepcion']) }}" class="w-full p-2.5 bg-brand-canvas border border-brand-border hover:border-slate-400 rounded-lg text-left text-xs font-medium flex justify-between items-center text-brand-slate transition-colors">
                    <span>Recepción e Ingreso</span>
                    <span class="text-brand-muted font-mono text-[10px]">INTAKE</span>
                </a>
                <a href="{{ route('dashboard', ['rol' => 'almacen']) }}" class="w-full p-2.5 bg-brand-canvas border border-brand-border hover:border-slate-400 rounded-lg text-left text-xs font-medium flex justify-between items-center text-brand-slate transition-colors">
                    <span>Almacén de Partes</span>
                    <span class="text-brand-muted font-mono text-[10px]">STOCK</span>
                </a>
                <a href="{{ route('dashboard', ['rol' => 'cliente']) }}" class="w-full p-2.5 bg-brand-canvas border border-brand-border hover:border-slate-400 rounded-lg text-left text-xs font-medium flex justify-between items-center text-brand-slate transition-colors">
                    <span>Portal Cliente / Empresa</span>
                    <span class="text-brand-muted font-mono text-[10px]">CLIENT</span>
                </a>
            </div>
            <div class="border-t border-brand-border pt-2.5 text-center">
                <a href="{{ url('/#portal-acceso') }}" onclick="closeAuthModal()" class="text-xs text-brand-navy font-medium hover:underline">
                    Ver formulario completo de acceso
                </a>
            </div>
        </div>
    </div>

    <script>
        function openAuthModal() {
            const modal = document.getElementById('authModalBackdrop');
            if (modal) modal.classList.remove('hidden');
        }

        function closeAuthModal() {
            const modal = document.getElementById('authModalBackdrop');
            if (modal) modal.classList.add('hidden');
        }
    </script>

    @stack('scripts')
</body>
</html>
