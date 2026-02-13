<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Номера
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex flex-col sm:flex-row gap-2 sm:items-center">
                <form method="GET" action="{{ route('rooms.index') }}" class="flex gap-2">
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Поиск (номер/название)"
                           class="border rounded px-3 py-2 w-80">
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">Найти</button>
                </form>

                <a href="{{ route('rooms.create') }}"
                   class="sm:ml-auto px-4 py-2 bg-blue-600 text-white rounded">
                    + Добавить номер
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 text-gray-700 dark:text-gray-200">№</th>
                            <th class="text-gray-700 dark:text-gray-200">Тип</th>
                            <th class="text-gray-700 dark:text-gray-200">Цена/ночь</th>
                            <th class="text-gray-700 dark:text-gray-200">Вместимость</th>
                            <th class="text-gray-700 dark:text-gray-200">Активен</th>
                            <th class="text-right text-gray-700 dark:text-gray-200">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2 text-gray-800 dark:text-gray-200">
                                    {{ $room->number }}
                                    @if($room->title)
                                        <div class="text-xs text-gray-500">{{ $room->title }}</div>
                                    @endif
                                </td>
                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ $room->roomType?->name ?? '—' }}
                                </td>
                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ number_format($room->price_per_night, 2, '.', ' ') }}
                                </td>
                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ $room->capacity ?? '—' }}
                                </td>
                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ $room->is_active ? 'Да' : 'Нет' }}
                                </td>
                                <td class="text-right">
                                    <a class="text-blue-600" href="{{ route('rooms.edit', $room) }}">Редактировать</a>

                                    <form class="inline" method="POST" action="{{ route('rooms.destroy', $room) }}"
                                          onsubmit="return confirm('Удалить номер?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 ml-3">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-gray-500">Нет номеров</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $rooms->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
