@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Номера') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded text-gray-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex flex-col sm:flex-row gap-2 sm:items-center">
                <form method="GET" action="{{ route($prefix.'rooms.index') }}" class="flex gap-2">
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="{{ __('Поиск (номер/название)') }}"
                           class="border border-gray-300 dark:border-gray-700 rounded px-3 py-2 w-80 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        {{ __('Найти') }}
                    </button>
                </form>

                <a href="{{ route($prefix.'rooms.create') }}"
                   class="sm:ml-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    {{ __('+ Добавить номер') }}
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded p-4 overflow-auto text-gray-800 dark:text-gray-200">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2">{{ __('№') }}</th>
                            <th>{{ __('Тип') }}</th>
                            <th>{{ __('Цена/ночь') }}</th>
                            <th>{{ __('Вместимость') }}</th>
                            <th>{{ __('Активен') }}</th>
                            <th class="text-right">{{ __('Действия') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2">
                                    {{ $room->number }}
                                    @if($room->title)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $room->title }}</div>
                                    @endif
                                </td>
                                <td>{{ $room->roomType?->name ?? '—' }}</td>
                                <td>{{ number_format($room->price_per_night, 2, '.', ' ') }}</td>
                                <td>{{ $room->capacity ?? '—' }}</td>
                                <td>{{ $room->is_active ? __('Да') : __('Нет') }}</td>
                                <td class="text-right whitespace-nowrap">
                                    <a class="text-blue-600 dark:text-blue-300" href="{{ route($prefix.'rooms.edit', $room) }}">
                                        {{ __('Редактировать') }}
                                    </a>
                                    <form method="POST" action="{{ route($prefix.'rooms.destroy', $room) }}" class="inline" onsubmit="return confirm('{{ __('Удалить номер?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 dark:text-red-300 ml-3">{{ __('Удалить') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-4 text-center text-gray-500 dark:text-gray-400">{{ __('Нет номеров') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $rooms->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>