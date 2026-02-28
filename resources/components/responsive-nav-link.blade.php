@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full px-3 py-2 rounded-md text-left text-base font-medium text-white bg-gray-800 transition'
    : 'block w-full px-3 py-2 rounded-md text-left text-base font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>