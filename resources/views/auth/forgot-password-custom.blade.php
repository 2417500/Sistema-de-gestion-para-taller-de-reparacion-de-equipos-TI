<x-guest-layout>
    <!-- ESTADO DE SESIÓN -->
    <x-auth-session-status class="mb-4 text-emerald-400 font-mono text-xs" :status="session('status')" />

    @if (session('success'))
        <div class="mb-4 p-3 bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 font-mono text-xs rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- CABECERA SIN EL TEXTO lock_reset -->
    <div class="mb-6 border-b border-[#588157] pb-3 flex items-center gap-2">
        <svg class="w-5 h-5 fill-current text-[#a3b18a]" viewBox="0 0 24 24">
            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
        </svg>
        <h2 class="text-base font-bold uppercase tracking-wider dynamic-title font-titulos">Recuperación de Contraseña</h2>
    </div>

    <!-- FORMULARIO DE RECUPERACIÓN -->
    <form method="POST" action="{{ route('password.custom.request') }}" class="space-y-5">
        @csrf

        <!-- MÉTODO DE RECUPERACIÓN -->
        <div>
            <label for="metodo" class="block text-xs font-bold uppercase dynamic-muted font-subtitulos mb-1">
                MÉTODO DE RECUPERACIÓN *
            </label>
            <select id="metodo" name="metodo" 
                    class="w-full px-3 py-2.5 rounded text-xs outline-none border border-[#588157] dynamic-title focus:border-[#a3b18a]"
                    style="background-color: var(--bg-input);">
                <option value="sms" class="bg-[#3a5a40] text-white">Opción 1: SMS Simulado (Teléfono Celular)</option>
                <option value="email" class="bg-[#3a5a40] text-white">Opción 2: Correo Electrónico</option>
            </select>
        </div>

        <!-- INPUT DE CELULAR O CORREO -->
        <div>
            <label for="contacto" class="block text-xs font-bold uppercase dynamic-muted font-subtitulos mb-1">
                INGRESA TU CELULAR O CORREO *
            </label>
            <input id="contacto" type="text" name="contacto" required autofocus
                   placeholder="Ej: 5512345678 o correo@gmail.com"
                   class="block w-full px-3 py-2.5 rounded text-xs outline-none focus:border-[#588157]" />
            <x-input-error :messages="$errors->get('contacto')" class="mt-1 text-xs text-rose-400 font-mono" />
        </div>

        <!-- BOTÓN CON LA NUEVA PALETA VERDE CORPORATIVA -->
        <div class="pt-2">
            <button type="submit" 
                    class="w-full py-3 bg-[#a3b18a] text-[#344e41] font-bold text-xs uppercase tracking-wider rounded hover:bg-[#588157] hover:text-white transition-colors shadow">
                GENERAR CÓDIGO DE SEGURIDAD
            </button>
        </div>

        <!-- ENLACE REGRESO -->
        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="dynamic-muted hover:underline text-xs font-subtitulos transition-colors">
                ← Volver a Identificación de Terminal
            </a>
        </div>
    </form>
</x-guest-layout>