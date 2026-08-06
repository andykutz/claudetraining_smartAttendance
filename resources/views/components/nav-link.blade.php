@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium leading-5 bg-white/15 text-white focus:outline-none transition-all duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium leading-5 text-blue-200 hover:text-white hover:bg-white/5 focus:outline-none transition-all duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
