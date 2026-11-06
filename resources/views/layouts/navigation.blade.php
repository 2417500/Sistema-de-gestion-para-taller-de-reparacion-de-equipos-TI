<nav x-data="{ open: false }" class="bg-surface-container-lowest border-b border-outline-variant">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <span class="px-2 py-1 bg-primary-container text-noir-black font-mono font-black text-sm tracking-widest rounded-sm">SF</span>
                        <span class="text-white font-black tracking-wider text-sm uppercase">SoporTec</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-xs font-mono uppercase tracking-wider">
                        {{ __('Workbench Dashboard') }}
                    </x-nav-link>

                    @auth
                        @php
                            $rolNombre = strtolower(trim(Auth::user()->role->nombre ?? 'cliente'));
                        @endphp

                        <!-- Módulos para Personal Técnico / Interno -->
                        @if(!str_contains($rolNombre, 'client'))
                            <x-nav-link :href="route('recepcion.index')" :active="request()->routeIs('recepcion.*')" class="text-xs font-mono uppercase tracking-wider">
                                {{ __('Recepción') }}
                            </x-nav-link>

                            <x-nav-link :href="route('banco.index')" :active="request()->routeIs('banco.*')" class="text-xs font-mono uppercase tracking-wider">
                                {{ __('Banco Técnico') }}
                            </x-nav-link>

                            <x-nav-link :href="route('aprobaciones.index')" :active="request()->routeIs('aprobaciones.*')" class="text-xs font-mono uppercase tracking-wider">
                                {{ __('Aprobaciones') }}
                            </x-nav-link>

                            <x-nav-link :href="route('almacen.index')" :active="request()->routeIs('almacen.*')" class="text-xs font-mono uppercase tracking-wider">
                                {{ __('Almacén') }}
                            </x-nav-link>
                        @endif

                        <!-- Opción Exclusiva para Administrador -->
                        @if(Auth::user()->role && Auth::user()->role->nombre === 'Administrador')
                            <x-nav-link :href="route('admin.users.create')" :active="request()->routeIs('admin.users.create')" class="text-xs font-mono uppercase tracking-wider text-primary-container">
                                <span class="material-symbols-outlined text-sm mr-1">badge</span>
                                {{ __('Crear Empleados') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-outline-variant text-xs font-mono rounded-md text-on-surface bg-surface-container hover:border-primary-container focus:outline-none transition ease-in-out duration-150">
                            <div class="flex flex-col text-left mr-2">
                                <span class="font-bold text-white">{{ Auth::user()->name }}</span>
                                <span class="text-[10px] text-primary-container uppercase">{{ Auth::user()->role->nombre ?? 'Usuario' }}</span>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="font-mono text-xs">
                            {{ __('Perfil de Usuario') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="font-mono text-xs text-red-400">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'inline-flex': ! open, 'hidden': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-outline-variant bg-surface-container-low">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @auth
                @php
                    $rolNombre = strtolower(trim(Auth::user()->role->nombre ?? 'cliente'));
                @endphp

                @if(!str_contains($rolNombre, 'client'))
                    <x-responsive-nav-link :href="route('recepcion.index')" :active="request()->routeIs('recepcion.*')">
                        {{ __('Recepción') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('banco.index')" :active="request()->routeIs('banco.*')">
                        {{ __('Banco Técnico') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('aprobaciones.index')" :active="request()->routeIs('aprobaciones.*')">
                        {{ __('Aprobaciones') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('almacen.index')" :active="request()->routeIs('almacen.*')">
                        {{ __('Almacén') }}
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->role && Auth::user()->role->nombre === 'Administrador')
                    <x-responsive-nav-link :href="route('admin.users.create')" :active="request()->routeIs('admin.users.create')">
                        {{ __('Crear Empleados') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-outline-variant">
            <div class="px-4">
                <div class="font-bold text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-mono text-xs text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>