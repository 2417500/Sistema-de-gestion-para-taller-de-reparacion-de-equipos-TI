@extends('layouts.public')

@section('title', 'TecnoFix Solutions | Taller de Reparación Especializada de Equipos TI')

@section('content')
    <!-- ========================================== -->
    <!-- HERO SECTION                               -->
    <!-- ========================================== -->
    <section class="pt-16 pb-16 px-4 sm:px-8 border-b border-brand-border bg-white">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Status Pill -->
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-amber-light border border-brand-amber-border text-[#92400E] text-xs font-medium mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber animate-pulse"></span>
                <span>Laboratorio activo • Diagnóstico en &lt; 24h</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-bold tracking-tight text-brand-slate max-w-3xl mx-auto leading-tight mb-5">
                Ingeniería y diagnóstico especializado para hardware TI empresarial
            </h1>
            <p class="text-base sm:text-lg text-brand-muted max-w-2xl mx-auto mb-10 leading-relaxed">
                Reparación a nivel de componente, microelectrónica y recuperación crítica para servidores, laptops corporativas y almacenamiento. Sin rodeos.
            </p>

            <!-- Sleek Single-Input Hardware Tracking Field -->
            <div id="rastreo" class="max-w-xl mx-auto bg-brand-canvas border border-brand-border p-2 rounded-lg shadow-sm mb-10">
                <form class="flex flex-col sm:flex-row gap-2" onsubmit="handleTrackTicket(event)">
                    <div class="relative flex-grow">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-brand-muted">
                            <span class="material-symbols-outlined text-sm">qr_code_scanner</span>
                        </span>
                        <input id="ticketInput" type="text" required placeholder="Número de ticket (Ej. TFX-9842)"
                            class="w-full pl-9 pr-3 py-2 text-sm bg-white border border-brand-border rounded-lg text-brand-slate placeholder:text-slate-400 focus:border-brand-navy focus:ring-0 uppercase outline-none font-mono tabular-nums" />
                    </div>
                    <button type="submit" class="px-5 py-2 bg-brand-navy hover:bg-brand-navy-hover text-white text-xs font-semibold rounded-lg tracking-wide transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                        <span>Rastrear Orden</span>
                        <span class="material-symbols-outlined text-xs">arrow_forward</span>
                    </button>
                </form>

                <!-- Tracking Feedback Area -->
                <div id="trackingFeedback" class="hidden mt-2 p-3 bg-white border border-brand-border rounded-lg text-left transition-all">
                    <div class="flex items-center justify-between">
                        <div class="space-y-0.5">
                            <span id="trackStatus" class="inline-block text-xs font-semibold text-brand-amber bg-brand-amber-light px-2 py-0.5 rounded">
                                En Reparación • Bay 02
                            </span>
                            <p id="trackDetail" class="text-xs text-brand-slate font-medium pt-1">
                                Ticket #TFX-9842 — Dell PowerEdge R740 (VRM reemplazado)
                            </p>
                        </div>
                        <span class="text-xs font-mono text-brand-muted tabular-nums">ETA: 4h 30m</span>
                    </div>
                </div>
            </div>

            <!-- Metrics Strip -->
            <div class="grid grid-cols-3 divide-x divide-brand-border max-w-lg mx-auto pt-4 border-t border-brand-border text-center tabular-nums">
                <div class="px-2">
                    <div class="text-xl font-bold text-brand-slate">14,000+</div>
                    <div class="text-xs text-brand-muted mt-0.5">Equipos reparados</div>
                </div>
                <div class="px-2">
                    <div class="text-xl font-bold text-brand-amber">99.2%</div>
                    <div class="text-xs text-brand-muted mt-0.5">Éxito en placa base</div>
                </div>
                <div class="px-2">
                    <div class="text-xl font-bold text-brand-slate">180 días</div>
                    <div class="text-xs text-brand-muted mt-0.5">Garantía escrita</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- CORE SERVICES (Clean Minimalist Cards)     -->
    <!-- ========================================== -->
    <section id="servicios" class="py-16 px-4 sm:px-8 border-b border-brand-border">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 pb-4 border-b border-brand-border gap-4">
                <div>
                    <span class="text-xs font-semibold tracking-wider text-brand-amber uppercase">Capacidades de Taller</span>
                    <h2 class="text-2xl font-bold tracking-tight text-brand-slate mt-1">Servicios Técnicos Especializados</h2>
                </div>
                <p class="text-sm text-brand-muted max-w-md">
                    Instrumental de precisión, diagnóstico térmico no invasivo y microsoldadura bajo estrictos estándares industriales.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card 1 -->
                <div class="bg-white border border-brand-border rounded-lg p-6 hover:border-slate-400 transition-all flex flex-col justify-between shadow-sm">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-lg bg-brand-canvas border border-brand-border flex items-center justify-center text-brand-navy">
                                <span class="material-symbols-outlined text-xl">dns</span>
                            </div>
                            <span class="text-xs font-medium px-2.5 py-0.5 bg-slate-100 text-slate-600 rounded-full">Enterprise</span>
                        </div>
                        <h3 class="text-base font-semibold text-brand-slate mb-2">Servidores &amp; Workstations</h3>
                        <p class="text-sm text-brand-muted mb-5 leading-relaxed">
                            Intervención en tarjetas madre multiprocesador de alta disponibilidad sin sustitución total de chasis.
                        </p>
                        <ul class="space-y-2 text-xs text-slate-700 border-t border-brand-border pt-4 mb-4">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span>
                                <span>Reparación de rieles VRM y líneas Xeon / EPYC</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span>
                                <span>Sustitución de zócalos BGA y chipsets de servidor</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span>
                                <span>Backplanes SAS/NVMe y fuentes de poder redundantes</span>
                            </li>
                        </ul>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-brand-muted">
                        <span class="font-mono">SLA: &lt; 24h</span>
                        <span class="font-medium text-brand-slate">Dell, HPE, Lenovo, Supermicro</span>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white border border-brand-border rounded-lg p-6 hover:border-slate-400 transition-all flex flex-col justify-between shadow-sm">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-lg bg-brand-canvas border border-brand-border flex items-center justify-center text-brand-navy">
                                <span class="material-symbols-outlined text-xl">laptop_chromebook</span>
                            </div>
                            <span class="text-xs font-medium px-2.5 py-0.5 bg-slate-100 text-slate-600 rounded-full">Flotas Pro</span>
                        </div>
                        <h3 class="text-base font-semibold text-brand-slate mb-2">Laptops &amp; Ultrabooks</h3>
                        <p class="text-sm text-brand-muted mb-5 leading-relaxed">
                            Diagnóstico de fallas en series ThinkPad, Latitude, EliteBook y MacBook Pro con cámaras térmicas.
                        </p>
                        <ul class="space-y-2 text-xs text-slate-700 border-t border-brand-border pt-4 mb-4">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span>
                                <span>Reballing y reemplazo de controladores GPU dedicados</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span>
                                <span>Solución de cortocircuitos en rieles de carga primarios</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span>
                                <span>Reparación de interfaces Thunderbolt, USB-C y pantalla</span>
                            </li>
                        </ul>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-brand-muted">
                        <span class="font-mono">Reparación Nivel 4</span>
                        <span class="font-medium text-brand-slate">Componentes OEM</span>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white border border-brand-border rounded-lg p-6 hover:border-slate-400 transition-all flex flex-col justify-between shadow-sm">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-lg bg-brand-canvas border border-brand-border flex items-center justify-center text-brand-navy">
                                <span class="material-symbols-outlined text-xl">security</span>
                            </div>
                            <span class="text-xs font-medium px-2.5 py-0.5 bg-brand-amber-light text-[#92400E] rounded-full">ISO 5 Cleanroom</span>
                        </div>
                        <h3 class="text-base font-semibold text-brand-slate mb-2">Recuperación de Datos</h3>
                        <p class="text-sm text-brand-muted mb-5 leading-relaxed">
                            Extracción forense en ambiente estéril para medios mecánicos dañados, quemados o con falla lógica severa.
                        </p>
                        <ul class="space-y-2 text-xs text-slate-700 border-t border-brand-border pt-4 mb-4">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span>
                                <span>Cambio de cabezales y platos en campana de flujo laminar</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span>
                                <span>Extracción directa de memorias NAND Flash en SSD quemados</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span>
                                <span>Reconstrucción de arreglos RAID colapsados (0, 1, 5, 10, 50)</span>
                            </li>
                        </ul>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-brand-muted">
                        <span class="font-mono">Confidencialidad NDA</span>
                        <span class="font-medium text-brand-slate">ISO 27001 Certified</span>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white border border-brand-border rounded-lg p-6 hover:border-slate-400 transition-all flex flex-col justify-between shadow-sm">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-lg bg-brand-canvas border border-brand-border flex items-center justify-center text-brand-navy">
                                <span class="material-symbols-outlined text-xl">device_thermostat</span>
                            </div>
                            <span class="text-xs font-medium px-2.5 py-0.5 bg-slate-100 text-slate-600 rounded-full">Optimización</span>
                        </div>
                        <h3 class="text-base font-semibold text-brand-slate mb-2">Mantenimiento Térmico Crítico</h3>
                        <p class="text-sm text-brand-muted mb-5 leading-relaxed">
                            Supresión definitiva de estrangulamiento térmico en estaciones de trabajo y servidores de alta carga.
                        </p>
                        <ul class="space-y-2 text-xs text-slate-700 border-t border-brand-border pt-4 mb-4">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span>
                                <span>Aplicación de metal líquido / compuesto de alto rendimiento</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span>
                                <span>Reemplazo por almohadillas térmicas industriales 15.0 W/mK</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span>
                                <span>Limpieza ultrasónica de cámaras de vapor y heatpipes</span>
                            </li>
                        </ul>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-brand-muted">
                        <span class="font-mono">Reducción hasta -18°C</span>
                        <span class="font-medium text-brand-slate">Estrés test FurMark &amp; AIDA64</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- HORARIOS & UBICACIÓN COMPACTOS             -->
    <!-- ========================================== -->
    <section id="horarios" class="py-16 px-4 sm:px-8 border-b border-brand-border bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Horarios -->
                <div>
                    <span class="text-xs font-semibold tracking-wider text-brand-amber uppercase">Disponibilidad</span>
                    <h2 class="text-xl font-bold tracking-tight text-brand-slate mt-1 mb-5">Horarios de Atención y Recepción</h2>
                    <div class="space-y-3">
                        <div class="bg-brand-canvas border border-brand-border rounded-lg p-4 flex items-center justify-between">
                            <div>
                                <div class="text-sm font-semibold text-brand-slate">Turno Taller Regular</div>
                                <div class="text-xs text-brand-muted">Lunes a Viernes • Atención presencial y diagnósticos</div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-mono font-semibold text-brand-navy bg-white px-2.5 py-1 border border-brand-border rounded tabular-nums">08:00 - 19:00</span>
                            </div>
                        </div>
                        <div class="bg-brand-amber-light/40 border border-brand-amber-border rounded-lg p-4 flex items-center justify-between">
                            <div>
                                <div class="text-sm font-semibold text-[#92400E] flex items-center gap-1.5">
                                    <span>Soporte Crítico Datacenter</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-amber"></span>
                                </div>
                                <div class="text-xs text-[#92400E]/80">Guardia técnica permanente para infraestructura enterprise</div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-mono font-semibold text-[#92400E] bg-white px-2.5 py-1 border border-brand-amber-border rounded tabular-nums">24 / 7 / 365</span>
                            </div>
                        </div>
                        <div class="bg-brand-canvas border border-brand-border rounded-lg p-4 flex items-center justify-between">
                            <div>
                                <div class="text-sm font-semibold text-brand-slate">Recepción Courier &amp; Sábados</div>
                                <div class="text-xs text-brand-muted">Recepción de paquetería asegurada y entregas programadas</div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-mono font-semibold text-brand-navy bg-white px-2.5 py-1 border border-brand-border rounded tabular-nums">09:00 - 14:00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ubicación del Taller -->
                <div id="taller">
                    <span class="text-xs font-semibold tracking-wider text-brand-amber uppercase">Instalaciones</span>
                    <h2 class="text-xl font-bold tracking-tight text-brand-slate mt-1 mb-5">Ubicación &amp; Acceso Seguro</h2>
                    <div class="bg-brand-canvas border border-brand-border rounded-lg p-5 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white border border-brand-border flex items-center justify-center text-brand-navy shrink-0 shadow-xs">
                                <span class="material-symbols-outlined text-base">location_on</span>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-brand-slate">Laboratorio Central El Marqués</div>
                                <div class="text-xs text-brand-muted mt-0.5">Av. Tecnológica 1040, Módulo C-4, Hub Industrial El Marqués</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 border-t border-brand-border pt-3">
                            <div class="w-8 h-8 rounded-lg bg-white border border-brand-border flex items-center justify-center text-brand-navy shrink-0 shadow-xs">
                                <span class="material-symbols-outlined text-base">verified_user</span>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-brand-slate">Área ESD Safe &amp; Filtros HEPA H14</div>
                                <div class="text-xs text-brand-muted mt-0.5">Protección antiestática certificada y acceso biométrico controlado</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between border-t border-brand-border pt-3 text-xs text-brand-muted">
                            <span class="font-mono tabular-nums">LAT: 20.5888° N • LON: -100.3899° W</span>
                            <a href="#taller" class="text-brand-navy font-medium hover:underline inline-flex items-center gap-1">
                                <span>Andén 04 de Carga</span>
                                <span class="material-symbols-outlined text-xs">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- POLÍTICAS & GARANTÍAS                      -->
    <!-- ========================================== -->
    <section id="politicas" class="py-16 px-4 sm:px-8 border-b border-brand-border">
        <div class="max-w-6xl mx-auto">
            <div class="text-center max-w-xl mx-auto mb-12">
                <span class="text-xs font-semibold tracking-wider text-brand-amber uppercase">Transparencia Operativa</span>
                <h2 class="text-2xl font-bold tracking-tight text-brand-slate mt-1">Políticas del Taller &amp; Garantías</h2>
                <p class="text-sm text-brand-muted mt-2">Compromiso formal por escrito en cada intervención técnica.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-brand-border rounded-lg p-5 shadow-sm">
                    <span class="text-xs font-mono font-bold text-brand-amber block mb-2">01 / DIAGNÓSTICO</span>
                    <h3 class="text-sm font-bold text-brand-slate mb-1.5">Diagnóstico en 24h</h3>
                    <p class="text-xs text-brand-muted leading-relaxed">
                        Dictamen técnico formal antes de realizar cualquier intervención física o cargo extra.
                    </p>
                </div>
                <div class="bg-white border border-brand-border rounded-lg p-5 shadow-sm">
                    <span class="text-xs font-mono font-bold text-brand-amber block mb-2">02 / PRIVACIDAD</span>
                    <h3 class="text-sm font-bold text-brand-slate mb-1.5">NDA &amp; Confidencialidad</h3>
                    <p class="text-xs text-brand-muted leading-relaxed">
                        Acuerdo de confidencialidad estricto. Cero clonación innecesaria ni acceso no autorizado a datos.
                    </p>
                </div>
                <div class="bg-white border border-brand-border rounded-lg p-5 shadow-sm">
                    <span class="text-xs font-mono font-bold text-brand-amber block mb-2">03 / COBERTURA</span>
                    <h3 class="text-sm font-bold text-brand-slate mb-1.5">Garantía de 180 Días</h3>
                    <p class="text-xs text-brand-muted leading-relaxed">
                        Respaldo total sobre las piezas sustituidas y líneas de alimentación reparadas en placa.
                    </p>
                </div>
                <div class="bg-white border border-brand-border rounded-lg p-5 shadow-sm">
                    <span class="text-xs font-mono font-bold text-brand-amber block mb-2">04 / CALIDAD</span>
                    <h3 class="text-sm font-bold text-brand-slate mb-1.5">Piezas Certificadas</h3>
                    <p class="text-xs text-brand-muted leading-relaxed">
                        Componentes OEM directos y pasivos de grado industrial con trazabilidad garantizada.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- PORTAL DE ACCESO & ROLES SAAS              -->
    <!-- ========================================== -->
    <section id="portal-acceso" class="py-16 px-4 sm:px-8 bg-white border-b border-brand-border">
        <div class="max-w-xl mx-auto">
            <div class="text-center mb-8">
                <span class="text-xs font-semibold tracking-wider text-brand-amber uppercase">Acceso Centralizado</span>
                <h2 class="text-2xl font-bold tracking-tight text-brand-slate mt-1">Portal TecnoFix</h2>
                <p class="text-sm text-brand-muted mt-1">Plataforma de seguimiento técnico y gestión de reparaciones.</p>
            </div>

            <!-- Auth Card -->
            <div class="bg-white border border-brand-border rounded-lg p-6 sm:p-8 shadow-sm">
                <!-- Role selector demo pills -->
                <div class="mb-6 pb-6 border-b border-brand-border">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-brand-muted">Probar roles del sistema:</span>
                        <span class="text-[11px] font-mono text-brand-amber">5 Roles configurados</span>
                    </div>
                    <div id="roleSelectorGroup" class="grid grid-cols-2 sm:grid-cols-5 gap-1.5">
                        <button type="button" onclick="selectRole('Admin')" class="role-btn px-2.5 py-1.5 text-xs font-medium rounded-lg border border-brand-border text-brand-muted hover:text-brand-slate hover:bg-slate-50 transition-colors">Admin</button>
                        <button type="button" onclick="selectRole('Recepción')" class="role-btn px-2.5 py-1.5 text-xs font-medium rounded-lg border border-brand-border text-brand-muted hover:text-brand-slate hover:bg-slate-50 transition-colors">Recepción</button>
                        <button type="button" onclick="selectRole('Técnico')" class="role-btn px-2.5 py-1.5 text-xs font-medium rounded-lg border border-brand-navy bg-brand-navy text-white transition-colors">Técnico</button>
                        <button type="button" onclick="selectRole('Almacén')" class="role-btn px-2.5 py-1.5 text-xs font-medium rounded-lg border border-brand-border text-brand-muted hover:text-brand-slate hover:bg-slate-50 transition-colors">Almacén</button>
                        <button type="button" onclick="selectRole('Cliente')" class="role-btn px-2.5 py-1.5 text-xs font-medium rounded-lg border border-brand-border text-brand-muted hover:text-brand-slate hover:bg-slate-50 transition-colors col-span-2 sm:col-span-1">Cliente</button>
                    </div>
                    <div id="roleDescription" class="mt-2 text-xs text-brand-muted bg-brand-canvas p-2 rounded border border-brand-border">
                        Rol Activo: Técnico de Taller — Diagnóstico de circuitos, asignación de repuestos y órdenes activas.
                    </div>
                </div>

                <!-- Auth Tab Switcher -->
                <div class="flex border-b border-brand-border mb-5">
                    <button type="button" id="tabLoginBtn" onclick="switchAuthTab('login')" class="pb-2.5 px-4 text-xs font-semibold text-brand-slate border-b-2 border-brand-navy transition-all">
                        Iniciar Sesión
                    </button>
                    <button type="button" id="tabRegisterBtn" onclick="switchAuthTab('register')" class="pb-2.5 px-4 text-xs font-medium text-brand-muted border-b-2 border-transparent hover:text-brand-slate transition-all">
                        Registro Empresa
                    </button>
                </div>

                <!-- Login Form -->
                <div id="loginFormContainer" class="space-y-4">
                    <form action="{{ route('dashboard') }}" method="GET">
                        <input type="hidden" id="selectedRoleInput" name="rol" value="tecnico" />
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-brand-slate mb-1">Correo electrónico</label>
                                <input type="email" required value="tecnico.senior@tecnofix.com"
                                    class="w-full text-sm px-3 py-2 bg-white border border-brand-border rounded-lg text-brand-slate focus:border-brand-navy focus:ring-0 outline-none" />
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <label class="block text-xs font-medium text-brand-slate">Contraseña</label>
                                    <a href="#" class="text-xs text-brand-muted hover:text-brand-slate">¿Olvidó su clave?</a>
                                </div>
                                <input type="password" required value="HardwareKey2024"
                                    class="w-full text-sm px-3 py-2 bg-white border border-brand-border rounded-lg text-brand-slate focus:border-brand-navy focus:ring-0 outline-none" />
                            </div>
                        </div>
                        <div class="flex items-center justify-between my-4 text-xs">
                            <label class="flex items-center gap-2 cursor-pointer text-brand-muted">
                                <input type="checkbox" checked class="rounded border-slate-300 text-brand-navy focus:ring-0" />
                                <span>Recordar sesión</span>
                            </label>
                            <span class="text-slate-400">2FA habilitado</span>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-brand-navy hover:bg-brand-navy-hover text-white text-xs font-semibold rounded-lg tracking-wide transition-colors shadow-sm">
                            Ingresar al Portal
                        </button>
                    </form>
                </div>

                <!-- Register Form -->
                <div id="registerFormContainer" class="space-y-4 hidden">
                    <form onsubmit="handleAuthSubmit(event, 'register')">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-brand-slate mb-1">Empresa / Razón Social</label>
                                <input type="text" required placeholder="Ej. Soluciones Cloud S.A."
                                    class="w-full text-sm px-3 py-2 bg-white border border-brand-border rounded-lg text-brand-slate focus:border-brand-navy focus:ring-0 outline-none" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-brand-slate mb-1">Correo corporativo</label>
                                <input type="email" required placeholder="infraestructura@empresa.com"
                                    class="w-full text-sm px-3 py-2 bg-white border border-brand-border rounded-lg text-brand-slate focus:border-brand-navy focus:ring-0 outline-none" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-brand-slate mb-1">Teléfono de contacto</label>
                                <input type="tel" required placeholder="+52 55 4123 9900"
                                    class="w-full text-sm px-3 py-2 bg-white border border-brand-border rounded-lg text-brand-slate focus:border-brand-navy focus:ring-0 outline-none" />
                            </div>
                        </div>
                        <div class="my-3 text-xs text-brand-muted">
                            <label class="flex items-start gap-2 cursor-pointer">
                                <input type="checkbox" required class="mt-0.5 rounded border-slate-300 text-brand-navy focus:ring-0" />
                                <span>Acepto términos de servicio y políticas de diagnóstico.</span>
                            </label>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-brand-navy hover:bg-brand-navy-hover text-white text-xs font-semibold rounded-lg tracking-wide transition-colors shadow-sm">
                            Solicitar Registro de Empresa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function handleTrackTicket(e) {
        e.preventDefault();
        const val = document.getElementById('ticketInput').value.trim();
        const feedback = document.getElementById('trackingFeedback');
        const trackStatus = document.getElementById('trackStatus');
        const trackDetail = document.getElementById('trackDetail');

        if (!val) return;

        feedback.classList.remove('hidden');
        trackStatus.textContent = 'En Reparación • Bay 02';
        trackDetail.textContent = `Ticket #${val.toUpperCase()} — Reparación de rieles VRM completada`;
    }

    const roleDescriptions = {
        'Admin': { text: 'Rol Activo: Administrador General — Métricas de taller, asignación de órdenes y facturación.', slug: 'admin' },
        'Recepción': { text: 'Rol Activo: Recepción & Admisión — Registro de hardware, actas de custodia y entrega.', slug: 'recepcion' },
        'Técnico': { text: 'Rol Activo: Técnico de Taller — Diagnóstico de circuitos, asignación de repuestos y órdenes activas.', slug: 'tecnico' },
        'Almacén': { text: 'Rol Activo: Almacén de Componentes — Control de stock BGA, insumos térmicos y repuestos OEM.', slug: 'almacen' },
        'Cliente': { text: 'Rol Activo: Portal Cliente — Consulta de estatus de órdenes en tiempo real y descarga de reportes.', slug: 'cliente' }
    };

    function selectRole(roleName) {
        const descElem = document.getElementById('roleDescription');
        const roleInput = document.getElementById('selectedRoleInput');
        if (roleDescriptions[roleName]) {
            descElem.textContent = roleDescriptions[roleName].text;
            if (roleInput) roleInput.value = roleDescriptions[roleName].slug;
        }

        const buttons = document.querySelectorAll('.role-btn');
        buttons.forEach(btn => {
            if (btn.textContent.trim() === roleName) {
                btn.className = 'role-btn px-2.5 py-1.5 text-xs font-medium rounded-lg border border-brand-navy bg-brand-navy text-white transition-colors';
            } else {
                btn.className = 'role-btn px-2.5 py-1.5 text-xs font-medium rounded-lg border border-brand-border text-brand-muted hover:text-brand-slate hover:bg-slate-50 transition-colors';
            }
        });
    }

    function switchAuthTab(tab) {
        const loginContainer = document.getElementById('loginFormContainer');
        const registerContainer = document.getElementById('registerFormContainer');
        const tabLoginBtn = document.getElementById('tabLoginBtn');
        const tabRegisterBtn = document.getElementById('tabRegisterBtn');

        if (tab === 'login') {
            loginContainer.classList.remove('hidden');
            registerContainer.classList.add('hidden');
            tabLoginBtn.className = 'pb-2.5 px-4 text-xs font-semibold text-brand-slate border-b-2 border-brand-navy transition-all';
            tabRegisterBtn.className = 'pb-2.5 px-4 text-xs font-medium text-brand-muted border-b-2 border-transparent hover:text-brand-slate transition-all';
        } else {
            loginContainer.classList.add('hidden');
            registerContainer.classList.remove('hidden');
            tabRegisterBtn.className = 'pb-2.5 px-4 text-xs font-semibold text-brand-slate border-b-2 border-brand-navy transition-all';
            tabLoginBtn.className = 'pb-2.5 px-4 text-xs font-medium text-brand-muted border-b-2 border-transparent hover:text-brand-slate transition-all';
        }
    }

    function handleAuthSubmit(e, type) {
        e.preventDefault();
        alert('Solicitud de cuenta registrada. Un asesor técnico verificará su registro.');
    }
</script>
@endpush
