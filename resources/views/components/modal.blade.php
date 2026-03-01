@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
])

@php
    $maxWidthClass = match ($maxWidth) {
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        default => 'sm:max-w-2xl',
    };
@endphp

<div
    x-data="{ show: @js($show) }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') show = true"
    x-on:close.window="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 z-50 px-4 py-6 overflow-y-auto"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        x-show="show"
        x-transition.opacity
        class="fixed inset-0 bg-black/70"
        x-on:click="show = false"
    ></div>

    {{-- Panel --}}
    <div
        x-show="show"
        x-transition
        class="relative mx-auto w-full {{ $maxWidthClass }} bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800"
        x-on:click.stop
    >
        {{ $slot }}
    </div>
</div>