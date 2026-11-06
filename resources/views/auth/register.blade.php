<x-guest-layout>
    <div class="mb-6 border-b border-outline-variant pb-3">
        <h2 class="text-headline-md font-bold text-white uppercase text-base tracking-wider">Alta de Nuevo Operador / Cliente</h2>
        <p class="text-on-surface-variant text-[11px] font-mono">Registro en el sistema SoporTec v4.2.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nombre Completo / Razón Social" />
            <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ing. Carlos Ramos" />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-error font-mono" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Correo Electrónico de Contacto" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="carlos@empresa.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-error font-mono" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Contraseña de Acceso" />
            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-error font-mono" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
            <x-text-input id="password_confirmation" class="block w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-error font-mono" />
        </div>

        <div class="pt-4 flex items-center justify-between border-t border-outline-variant/60">
            <a class="text-xs font-mono text-on-surface-variant hover:text-white uppercase transition-colors" href="{{ route('login') }}">
                ¿Ya registrado? Iniciar Sesión
            </a>

            <x-primary-button>
                Crear Registro
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
