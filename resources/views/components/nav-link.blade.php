@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-white bg-gray-800 transition'
    : 'inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>