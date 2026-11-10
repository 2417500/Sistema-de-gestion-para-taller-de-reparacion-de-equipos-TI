<x-guest-layout>
    <div class="space-y-4 font-mono text-xs text-white">
        <div class="flex items-center gap-2 pb-3 border-b border-outline-variant">
            <span class="material-symbols-outlined text-[#ffb800]">verified_user</span>
            <h2 class="text-sm font-bold uppercase tracking-wider">VALIDAR CÓDIGO & NUEVA CONTRASEÑA</h2>
        </div>

        <!-- ALERTA CON EL CÓDIGO SIMULADO -->
        @if (session('simulated_sms'))
            <div class="bg-amber-950/90 border-2 border-[#ffb800] text-[#ffb800] p-4 rounded font-bold text-center space-y-1 shadow-lg">
                <div class="flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined">sms</span>
                    <span class="uppercase text-[11px]">Mensaje de Sistema (Simulador)</span>
                </div>
                <p class="text-sm tracking-wide">{{ session('simulated_sms') }}</p>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rose-950/80 border border-rose-500/50 text-rose-300 p-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('password.custom.reset') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Código de 6 dígitos *</label>
                <input type="text" name="codigo" maxlength="6" required placeholder="123456"
                    class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 text-center text-lg font-bold tracking-widest rounded focus:border-[#ffb800] focus:outline-none">
            </div>

            <div>
                <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Nueva Contraseña *</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
            </div>

            <div>
                <label class="block text-[#8a8e9b] uppercase text-[10px] mb-1 font-bold">Confirmar Nueva Contraseña *</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••"
                    class="w-full bg-[#181a20] border border-[#2d3038] text-white p-2.5 rounded focus:border-[#ffb800] focus:outline-none">
            </div>

            <button type="submit" class="w-full py-2.5 bg-[#ffb800] text-[#0d0f12] font-extrabold uppercase rounded hover:bg-white transition-colors">
                CAMBIAR CONTRASEÑA Y ACCEDER
            </button>
        </form>
    </div>
</x-guest-layout>