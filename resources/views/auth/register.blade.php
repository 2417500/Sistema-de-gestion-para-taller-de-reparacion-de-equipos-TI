<x-guest-layout>
    <div class="mb-6 border-b border-[#588157] pb-3">
        <h2 class="text-base font-bold uppercase tracking-wider dynamic-title font-titulos">Registro de Cuenta de Cliente</h2>
        <p class="dynamic-muted text-[11px] font-subtitulos mt-1">Crea tu cuenta pública para dar seguimiento a tus equipos en SoporTec v4.2.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Nombre Completo / Razón Social -->
        <div>
            <x-input-label for="name" value="Nombre Completo / Razón Social" class="block text-xs font-bold uppercase dynamic-muted font-subtitulos mb-1" />
            <x-text-input id="name" class="block w-full px-3 py-2.5 rounded text-xs outline-none focus:border-[#588157]" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Juan Pérez" />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-400 font-mono" />
        </div>

        <!-- Correo Electrónico -->
        <div>
            <x-input-label for="email" value="Correo Electrónico de Contacto" class="block text-xs font-bold uppercase dynamic-muted font-subtitulos mb-1" />
            <x-text-input id="email" class="block w-full px-3 py-2.5 rounded text-xs outline-none focus:border-[#588157]" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="cliente@correo.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-400 font-mono" />
        </div>

        <!-- Teléfono de Contacto (si existe en tu modelo) -->
        @if(Schema::hasColumn('users', 'telefono'))
            <div>
                <x-input-label for="telefono" value="Número Telefónico de Contacto" class="block text-xs font-bold uppercase dynamic-muted font-subtitulos mb-1" />
                <x-text-input id="telefono" class="block w-full px-3 py-2.5 rounded text-xs outline-none focus:border-[#588157]" type="text" name="telefono" :value="old('telefono')" placeholder="55 1234 5678" />
                <x-input-error :messages="$errors->get('telefono')" class="mt-1 text-xs text-rose-400 font-mono" />
            </div>
        @endif

        <!-- Contraseña -->
        <div>
            <x-input-label for="password" value="Contraseña de Acceso" class="block text-xs font-bold uppercase dynamic-muted font-subtitulos mb-1" />
            <x-text-input id="password" class="block w-full px-3 py-2.5 rounded text-xs outline-none focus:border-[#588157]"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-400 font-mono" />
        </div>

        <!-- Confirmar Contraseña -->
        <div>
            <x-input-label for="password_confirmation" value="Confirmar Contraseña" class="block text-xs font-bold uppercase dynamic-muted font-subtitulos mb-1" />
            <x-text-input id="password_confirmation" class="block w-full px-3 py-2.5 rounded text-xs outline-none focus:border-[#588157]"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-rose-400 font-mono" />
        </div>

        <div class="pt-4 flex items-center justify-between border-t border-[#588157]">
            <a href="{{ route('login') }}" class="text-xs font-bold uppercase dynamic-muted hover:text-[#588157] transition-colors">
                ¿Ya registrado? Iniciar Sesión
            </a>

            <button type="submit" class="px-5 py-2.5 bg-[#a3b18a] text-[#344e41] font-bold text-xs uppercase tracking-wider rounded hover:bg-[#588157] hover:text-white transition-colors shadow">
                Crear Cuenta
            </button>
        </div>
    </form>
</x-guest-layout>