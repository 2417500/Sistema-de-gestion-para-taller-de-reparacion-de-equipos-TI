<x-guest-layout>
    <!-- Estado de Sesión / Mensaje de Éxito al Cambiar Contraseña -->
    <x-auth-session-status class="mb-4 text-emerald-400 font-mono text-xs" :status="session('status')" />

    @if (session('success'))
        <div class="mb-4 p-3 bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 font-mono text-xs rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 border-b border-outline-variant pb-3">
        <h2 class="text-headline-md font-bold text-white uppercase text-base tracking-wider">Identificación de Terminal</h2>
        <p class="text-on-surface-variant text-[11px] font-mono">Ingrese credenciales de operador técnico o cliente.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Credencial / Correo Electrónico" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="usuario@tecnofix.local" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-error font-mono" />
        </div>

        <!-- Contraseña -->
        <div>
            <x-input-label for="password" value="Contraseña de Seguridad" />
            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-error font-mono" />
        </div>

        <!-- Recordarme & Olvidé contraseña -->
        <div class="flex items-center justify-between text-xs font-mono pt-2">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" class="accent-amber-500 bg-surface-container border-outline-variant" name="remember">
                <span class="text-on-surface-variant text-[11px]">Recordar terminal</span>
            </label>

            <a class="text-on-surface-variant hover:text-primary-container text-[11px] underline transition-colors" href="{{ route('password.custom.request') }}">
                ¿Olvidó contraseña?
            </a>
        </div>

        <div class="pt-4 flex items-center justify-between border-t border-outline-variant/60">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="text-xs font-mono text-on-surface-variant hover:text-white uppercase transition-colors">
                    Registrar Cuenta
                </a>
            @endif

            <x-primary-button>
                Acceder al Workbench
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>