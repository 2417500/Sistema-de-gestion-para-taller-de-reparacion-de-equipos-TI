@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-surface-container-lowest border border-outline-variant text-white font-mono text-xs placeholder-on-surface-variant/40 focus:border-primary-container focus:ring-1 focus:ring-primary-container px-3 py-2 outline-none transition-colors']) }}>
