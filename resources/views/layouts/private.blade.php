<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'TecnoFix Solutions — Hardware Operations Platform')</title>

    <!-- Google Fonts: Jost, Josefin Sans, Space Grotesk & Geist -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&family=Jost:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
    <!-- Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback CDN para desarrollo sin Vite corriendo -->
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <script>
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            brand: {
                                navy: "#14213d",
                                "navy-hover": "#0e172a",
                                amber: "#d97706",
                                "amber-noir": "#fca311",
                                "amber-light": "#fef3c7",
                                slate: "#0f172a",
                                muted: "#64748b",
                                border: "#e2e8f0",
                                canvas: "#f8fafc",
                                card: "#ffffff"
                            }
                        },
                        fontFamily: {
                            sans: ["Jost", "sans-serif"],
                            display: ["Josefin Sans", "sans-serif"],
                            mono: ["JetBrains Mono", "monospace"]
                        },
                        borderRadius: {
                            card: "8px",
                            btn: "6px"
                        },
                        boxShadow: {
                            subtle: "0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.02)",
                            elevated: "0 4px 6px -1px rgba(15, 23, 42, 0.05), 0 2px 4px -2px rgba(15, 23, 42, 0.03)"
                        }
                    }
                }
            }
        </script>
        <style>
            body {
                font-family: 'Jost', sans-serif;
                background-color: #f8fafc;
                color: #0f172a;
            }
            h1, h2, h3, .font-display {
                font-family: 'Josefin Sans', sans-serif;
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
<body class="bg-brand-canvas text-slate-900 min-h-screen flex antialiased selection:bg-brand-navy selection:text-white">

    @php
        $activeRole = request('rol', $defaultRole ?? 'tecnico');
    @endphp

    <!-- ========================================== -->
    <!-- MINIMALIST SAAS SIDEBAR (PRIVATE SHELL)    -->
    <!-- ========================================== -->
    <aside class="fixed top-0 left-0 h-screen w-64 z-40 bg-white border-r border-brand-border flex flex-col justify-between py-5 px-4 select-none">
        <div class="flex flex-col space-y-6">
            <!-- Brand & Workspace Identity -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 px-2 group">
                <div class="w-8 h-8 rounded-btn bg-brand-navy text-white flex items-center justify-center font-display font-bold text-sm tracking-tight shadow-sm">
                    TF
                </div>
                <div class="flex flex-col">
                    <span class="font-display font-bold text-sm text-slate-900 tracking-tight group-hover:text-brand-navy">TecnoFix</span>
                    <span class="text-[11px] text-brand-muted font-medium uppercase tracking-wider">Hardware Ops</span>
                </div>
            </a>

            <!-- Quick Role Mode Switcher (Pill Selector) -->
            <div class="p-1 bg-slate-100 rounded-btn flex items-center gap-0.5 text-[11px] font-medium text-brand-muted">
                <button type="button" id="role-pill-tecnico" onclick="switchRoleView('tecnico')"
                    class="role-pill flex-1 py-1 px-1.5 rounded-[5px] transition-all text-center {{ $activeRole === 'tecnico' ? 'bg-white text-slate-900 font-semibold shadow-sm' : 'text-brand-muted hover:text-slate-800' }}">
                    Técnico
                </button>
                <button type="button" id="role-pill-recepcion" onclick="switchRoleView('recepcion')"
                    class="role-pill flex-1 py-1 px-1.5 rounded-[5px] transition-all text-center {{ $activeRole === 'recepcion' ? 'bg-white text-slate-900 font-semibold shadow-sm' : 'text-brand-muted hover:text-slate-800' }}">
                    Recep.
                </button>
                <button type="button" id="role-pill-admin" onclick="switchRoleView('admin')"
                    class="role-pill flex-1 py-1 px-1.5 rounded-[5px] transition-all text-center {{ $activeRole === 'admin' ? 'bg-white text-slate-900 font-semibold shadow-sm' : 'text-brand-muted hover:text-slate-800' }}">
                    Admin
                </button>
                <button type="button" id="role-pill-almacen" onclick="switchRoleView('almacen')"
                    class="role-pill flex-1 py-1 px-1.5 rounded-[5px] transition-all text-center {{ $activeRole === 'almacen' ? 'bg-white text-slate-900 font-semibold shadow-sm' : 'text-brand-muted hover:text-slate-800' }}">
                    Stock
                </button>
            </div>

            <!-- Navigation Clusters -->
            <nav class="flex flex-col space-y-1">
                <div class="px-2 pb-1 text-[11px] uppercase tracking-wider font-semibold text-slate-400">Plataforma</div>

                <button type="button" id="nav-item-tecnico" onclick="switchRoleView('tecnico')"
                    class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-btn font-medium text-sm transition-colors {{ $activeRole === 'tecnico' ? 'bg-slate-100 text-slate-900 border-l-2 border-brand-navy' : 'text-brand-muted hover:text-slate-900 hover:bg-slate-50' }}">
                    <span class="material-symbols-outlined text-[19px] {{ $activeRole === 'tecnico' ? 'text-brand-navy' : '' }}">build_circle</span>
                    <span>Taller / Diagnóstico</span>
                </button>

                <button type="button" id="nav-item-recepcion" onclick="switchRoleView('recepcion')"
                    class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-btn font-medium text-sm transition-colors {{ $activeRole === 'recepcion' ? 'bg-slate-100 text-slate-900 border-l-2 border-brand-navy' : 'text-brand-muted hover:text-slate-900 hover:bg-slate-50' }}">
                    <span class="material-symbols-outlined text-[19px] {{ $activeRole === 'recepcion' ? 'text-brand-navy' : '' }}">move_to_inbox</span>
                    <span>Recepción &amp; Admisión</span>
                </button>

                <button type="button" id="nav-item-admin" onclick="switchRoleView('admin')"
                    class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-btn font-medium text-sm transition-colors {{ $activeRole === 'admin' ? 'bg-slate-100 text-slate-900 border-l-2 border-brand-navy' : 'text-brand-muted hover:text-slate-900 hover:bg-slate-50' }}">
                    <span class="material-symbols-outlined text-[19px] {{ $activeRole === 'admin' ? 'text-brand-navy' : '' }}">query_stats</span>
                    <span>Métricas &amp; SLAs</span>
                </button>

                <button type="button" id="nav-item-almacen" onclick="switchRoleView('almacen')"
                    class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-btn font-medium text-sm transition-colors {{ $activeRole === 'almacen' ? 'bg-slate-100 text-slate-900 border-l-2 border-brand-navy' : 'text-brand-muted hover:text-slate-900 hover:bg-slate-50' }}">
                    <span class="material-symbols-outlined text-[19px] {{ $activeRole === 'almacen' ? 'text-brand-navy' : '' }}">inventory_2</span>
                    <span>Almacén de Partes</span>
                </button>

                <button type="button" id="nav-item-cliente" onclick="switchRoleView('cliente')"
                    class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-btn font-medium text-sm transition-colors {{ $activeRole === 'cliente' ? 'bg-slate-100 text-slate-900 border-l-2 border-brand-navy' : 'text-brand-muted hover:text-slate-900 hover:bg-slate-50' }}">
                    <span class="material-symbols-outlined text-[19px] {{ $activeRole === 'cliente' ? 'text-brand-navy' : '' }}">shield_person</span>
                    <span>Portal Cliente</span>
                </button>
            </nav>
        </div>

        <!-- User Profile & System Footer -->
        <div class="flex flex-col space-y-3 pt-4 border-t border-brand-border">
            <div class="flex items-center justify-between p-2 rounded-btn hover:bg-slate-50 transition-colors cursor-pointer">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-slate-100 border border-brand-border flex items-center justify-center font-display font-semibold text-xs text-brand-navy">
                        {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'CM' }}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-slate-800 leading-tight">
                            {{ auth()->check() ? auth()->user()->name : 'Carlos Mendoza' }}
                        </span>
                        <span class="text-[11px] text-brand-muted">Banco #04 • Activo</span>
                    </div>
                </div>
                <span class="w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-emerald-100" title="Online"></span>
            </div>
            <div class="flex items-center justify-between px-2 text-xs text-brand-muted">
                <span class="text-[11px]">TecnoFix v2.4 SaaS</span>
                <a href="{{ url('/') }}" class="hover:text-slate-900 transition-colors flex items-center gap-1" title="Volver al portal público / Cerrar sesión">
                    <span class="material-symbols-outlined text-[16px]">logout</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- ========================================== -->
    <!-- MAIN WORKSPACE & CONTENT                   -->
    <!-- ========================================== -->
    <div class="flex-1 flex flex-col min-w-0 pl-64">
        <!-- TOP ESSENTIAL APP BAR -->
        <header class="sticky top-0 z-30 flex items-center justify-between w-full h-14 px-8 bg-white/95 backdrop-blur-sm border-b border-brand-border">
            <!-- Global Search (Linear / Stripe Style) -->
            <div class="flex items-center gap-3 flex-1 max-w-md">
                <div class="relative w-full">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                    <input type="text" placeholder="Buscar orden, serie o cliente..."
                        class="w-full pl-9 pr-14 py-1.5 bg-slate-50 hover:bg-slate-100/70 focus:bg-white border border-brand-border rounded-btn text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-navy focus:border-brand-navy transition-all" />
                    <kbd class="absolute right-2.5 top-1/2 -translate-y-1/2 px-1.5 py-0.5 bg-white border border-brand-border rounded text-[10px] font-mono text-slate-400 shadow-xs">
                        ⌘K
                    </kbd>
                </div>
            </div>

            <!-- Action Items -->
            <div class="flex items-center gap-3">
                <!-- New Ticket Action -->
                <button type="button" onclick="openModal()"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-brand-navy hover:bg-slate-800 text-white text-xs font-medium rounded-btn shadow-subtle transition-colors">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    <span>Nueva Orden</span>
                </button>
                <div class="h-4 w-px bg-slate-200"></div>
                <!-- Notification Bell -->
                <button type="button" class="relative p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-btn transition-colors" title="Notificaciones">
                    <span class="material-symbols-outlined text-[20px]">notifications</span>
                    <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                </button>
                <!-- Help Link -->
                <a href="{{ url('/') }}" class="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-btn transition-colors" title="Ir al Portal Público">
                    <span class="material-symbols-outlined text-[20px]">public</span>
                </a>
            </div>
        </header>

        <!-- CONTENT BODY -->
        <main class="flex-1 p-8 space-y-6 max-w-7xl w-full mx-auto">
            @yield('content')
        </main>
    </div>

    <!-- ========================================== -->
    <!-- MODAL: NUEVA ORDEN                         -->
    <!-- ========================================== -->
    <div id="quick-order-modal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
        <div class="w-full max-w-lg bg-white rounded-card border border-brand-border shadow-elevated overflow-hidden">
            <div class="px-6 py-4 border-b border-brand-border flex items-center justify-between">
                <h3 class="text-sm font-bold font-display text-slate-900">Nueva Orden de Taller</h3>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Empresa / Cliente</label>
                    <input type="text" placeholder="Ej. Corporativo Bajío S.A."
                        class="w-full px-3 py-2 bg-slate-50 border border-brand-border rounded-btn text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-brand-navy" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Dispositivo &amp; Serie</label>
                    <input type="text" placeholder="Ej. MacBook Pro M2 Max - C02X..."
                        class="w-full px-3 py-2 bg-slate-50 border border-brand-border rounded-btn text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-brand-navy" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Sintomatología</label>
                    <textarea rows="3" placeholder="Falla reportada..."
                        class="w-full px-3 py-2 bg-slate-50 border border-brand-border rounded-btn text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-brand-navy"></textarea>
                </div>
                <div class="pt-3 border-t border-brand-border flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-100 rounded-btn">
                        Cancelar
                    </button>
                    <button type="button" onclick="submitOrder()" class="px-4 py-1.5 bg-brand-navy hover:bg-slate-800 text-white text-xs font-medium rounded-btn shadow-subtle">
                        Crear Orden
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchRoleView(roleId) {
            const sections = document.querySelectorAll('.role-section');
            if (sections.length > 0) {
                sections.forEach(sec => sec.classList.add('hidden'));
                const active = document.getElementById(`view-${roleId}`);
                if (active) active.classList.remove('hidden');
            }

            const navIds = ['tecnico', 'recepcion', 'admin', 'almacen', 'cliente'];
            navIds.forEach(id => {
                const nav = document.getElementById(`nav-item-${id}`);
                if (nav) {
                    if (id === roleId) {
                        nav.className = "nav-item w-full flex items-center gap-3 px-3 py-2 rounded-btn bg-slate-100 text-slate-900 font-medium text-sm transition-colors border-l-2 border-brand-navy";
                        const icon = nav.querySelector('.material-symbols-outlined');
                        if (icon) icon.className = "material-symbols-outlined text-[19px] text-brand-navy";
                    } else {
                        nav.className = "nav-item w-full flex items-center gap-3 px-3 py-2 rounded-btn text-brand-muted hover:text-slate-900 hover:bg-slate-50 font-medium text-sm transition-colors";
                        const icon = nav.querySelector('.material-symbols-outlined');
                        if (icon) icon.className = "material-symbols-outlined text-[19px]";
                    }
                }

                const pill = document.getElementById(`role-pill-${id}`);
                if (pill) {
                    if (id === roleId) {
                        pill.className = "role-pill flex-1 py-1 px-1.5 rounded-[5px] bg-white text-slate-900 font-semibold shadow-sm transition-all text-center";
                    } else {
                        pill.className = "role-pill flex-1 py-1 px-1.5 rounded-[5px] text-brand-muted hover:text-slate-800 transition-all text-center";
                    }
                }
            });
        }

        function openModal() {
            const modal = document.getElementById('quick-order-modal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('quick-order-modal');
            if (modal) modal.classList.add('hidden');
        }

        function submitOrder() {
            alert("Nueva orden #ORD-9822 creada y asignada a la fila de diagnóstico.");
            closeModal();
        }
    </script>

    @stack('scripts')
</body>
</html>
