@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2 rounded-lg bg-gray-200 text-gray-900 font-semibold shadow-sm transition duration-150 ease-in-out'
            : 'flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
