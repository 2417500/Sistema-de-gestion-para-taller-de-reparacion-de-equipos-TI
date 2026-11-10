<x-guest-layout>
    <div class="space-y-4 font-mono text-xs text-white">
        <div class="flex items-center gap-2 pb-3 border-b border-outline-variant">
            <span class="material-symbols-outlined text-[#ffb800]">lock_reset</span>
            <h2 class="text-sm font-bold uppercase tracking-wider">RECUPERACIÓN DE CONTRASEÑA</h2>
        </div>

        @if (session('error'))
            <div class="bg-rose-950/80 border border-rose-500/50 text-rose-300 p-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('password.custom.send') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Método de Recuperación *</label>
                <select name="metodo" required class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
                    <option value="sms">Opción 1: SMS Simulado (Teléfono Celular)</option>
                    <option value="email">Opción 2: Correo Electrónico (Gmail)</option>
                </select>
            </div>

            <div>
                <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Ingresa tu Celular o Correo *</label>
                <input type="text" name="contacto" required placeholder="Ej: 5512345678 o correo@gmail.com"
                    class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
            </div>

            <button type="submit" class="w-full py-2.5 bg-[#ffb800] text-[#0d0f12] font-extrabold uppercase rounded hover:bg-white transition-colors">
                GENERAR CÓDIGO DE SEGURIDAD
            </button>
        </form>

        <div class="pt-2 text-center">
            <a href="{{ route('login') }}" class="text-on-surface-variant hover:text-white text-[11px] underline">
                &larr; Volver a Identificación de Terminal
            </a>
        </div>
    </div>
</x-guest-layout>