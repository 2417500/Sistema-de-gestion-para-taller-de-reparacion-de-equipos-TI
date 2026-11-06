<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-primary-container text-noir-black border border-primary-container font-mono font-bold text-xs uppercase tracking-wider hover:bg-white hover:border-white transition-all active:translate-y-px amber-glow cursor-pointer']) }}>
    {{ $slot }}
</button>
