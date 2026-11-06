@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-mono text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-1']) }}>
    {{ $value ?? $slot }}
</label>
