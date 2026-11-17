<x-guest-layout>
    <!-- Estado de Sesión / Mensaje de Éxito al Cambiar Contraseña -->
    <x-auth-session-status class="mb-4 text-emerald-400 font-mono text-xs" :status="session('status')" />

    @if (session('success'))
        <div class="mb-4 p-3 bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 font-mono text-xs rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 border-b border-[#588157] pb-3">
        <h2 class="text-base font-bold uppercase tracking-wider dynamic-title font-titulos">Identificación de Terminal</h2>
        <p class="dynamic-muted text-[11px] font-subtitulos mt-1">Ingrese credenciales de operador técnico o cliente.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Credencial / Correo Electrónico" class="block text-xs font-bold uppercase dynamic-muted font-subtitulos mb-1" />
            <x-text-input id="email" class="block w-full px-3 py-2.5 rounded text-xs outline-none focus:border-[#588157]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="usuario@tecnofix.local" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-400 font-mono" />
        </div>

        <!-- Contraseña -->
        <div>
            <x-input-label for="password" value="Contraseña de Seguridad" class="block text-xs font-bold uppercase dynamic-muted font-subtitulos mb-1" />
            <x-text-input id="password" class="block w-full px-3 py-2.5 rounded text-xs outline-none focus:border-[#588157]"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-400 font-mono" />
        </div>

        <!-- Recordarme & Olvidé contraseña -->
        <div class="flex items-center justify-between text-xs pt-2">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" class="accent-[#588157] rounded border-[#588157]" name="remember">
                <span class="dynamic-muted text-[11px]">Recordar terminal</span>
            </label>

            <a class="dynamic-muted hover:underline text-[11px] transition-colors" href="{{ route('password.custom.request') }}">
                ¿Olvidó contraseña?
            </a>
        </div>

        <div class="pt-4 flex items-center justify-between border-t border-[#588157]">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="text-xs font-bold uppercase dynamic-muted hover:text-[#588157] transition-colors">
                    Registrar Cuenta
                </a>
            @endif

            <button type="submit" class="px-5 py-2.5 bg-[#a3b18a] text-[#344e41] font-bold text-xs uppercase tracking-wider rounded hover:bg-[#588157] hover:text-white transition-colors shadow">
                Acceder al Workbench
            </button>
        </div>
    </form>
</x-guest-layout>