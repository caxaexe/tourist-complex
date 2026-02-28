<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Отель — заявки на бронирование
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Информация про отель --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded p-5">
                <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">О нашем отеле</div>
                <div class="mt-2 text-gray-600 dark:text-gray-300">
                    Добавь сюда описание, правила, контакты, фото (если нужно).
                </div>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="rounded border border-gray-200 dark:border-gray-700 p-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Check-in</div>
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">14:00</div>
                    </div>
                    <div class="rounded border border-gray-200 dark:border-gray-700 p-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Check-out</div>
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">12:00</div>
                    </div>
                    <div class="rounded border border-gray-200 dark:border-gray-700 p-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Контакты</div>
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">+373 …</div>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">Подать заявку</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Выберите даты и номер — мы подтвердим.
                        </div>
                    </div>

                    <a href="{{ route('my.bookings.create') }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Подать заявку
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="p-3 bg-green-100 border border-green-300 rounded text-green-900">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Мои заявки --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded p-5 overflow-auto">
                <div class="flex items-center justify-between gap-3">
                    <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">Мои заявки</div>

                    <a href="{{ route('my.bookings.index') }}"
                       class="px-3 py-2 rounded border border-gray-200 dark:border-gray-700
                              text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                        Открыть список
                    </a>
                </div>

                <div class="mt-4">
                    <table class="w-full">
                        <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 text-gray-700 dark:text-gray-200">ID</th>
                            <th class="py-2 text-gray-700 dark:text-gray-200">Номер</th>
                            <th class="py-2 text-gray-700 dark:text-gray-200">Даты</th>
                            <th class="py-2 text-gray-700 dark:text-gray-200">Статус</th>
                            <th class="py-2 text-gray-700 dark:text-gray-200">Сумма</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($bookings as $b)
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $b->id }}</td>
                                <td class="py-2 text-gray-800 dark:text-gray-200">
                                    №{{ $b->room->number ?? '—' }}
                                    @if($b->room?->roomType)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $b->room->roomType->name }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-2 text-gray-800 dark:text-gray-200">
                                    {{ $b->date_from?->format('d.m.Y') }} — {{ $b->date_to?->format('d.m.Y') }}
                                </td>
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $b->status }}</td>
                                <td class="py-2 text-gray-800 dark:text-gray-200">
                                    {{ number_format((float)($b->total ?? 0), 2, '.', ' ') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-gray-500 dark:text-gray-400">
                                    Пока заявок нет - нажмите “Подать заявку”.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>