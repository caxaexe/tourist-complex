@props(['align' => 'right', 'width' => '48'])

@php
$alignmentClasses = $align === 'left' ? 'origin-top-left left-0' : 'origin-top-right right-0';
$widthClass = match ($width) {
    '48' => 'w-48',
    '56' => 'w-56',
    '64' => 'w-64',
    default => 'w-48',
};
@endphp

<div class="relative" x-data="{ open:false }" @keydown.escape.window="open=false">
    <div @click="open=!open">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition
        @click.outside="open=false"
        class="absolute z-50 mt-2 {{ $widthClass }} rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden {{ $alignmentClasses }}"
    >
        {{ $content }}
    </div>
</div>