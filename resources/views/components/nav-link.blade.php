@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link nav-link-active'
            : 'nav-link nav-link-idle';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
