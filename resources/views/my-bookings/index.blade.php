<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Мои заявки') }}
            </h2>

            <a href="{{ route('my.bookings.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700
                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                {{ __('Подать заявку') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="p-3 rounded bg-green-100 border border-green-300 text-green-900">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded p-5 overflow-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 text-gray-700 dark:text-gray-200">ID</th>
                            <th class="py-2 text-gray-700 dark:text-gray-200">{{ __('Номер') }}</th>
                            <th class="py-2 text-gray-700 dark:text-gray-200">{{ __('Даты') }}</th>
                            <th class="py-2 text-gray-700 dark:text-gray-200">{{ __('Статус') }}</th>
                            <th class="py-2 text-gray-700 dark:text-gray-200">{{ __('Сумма') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $b)
                            @php
                                $statusMap = [
                                    'pending'     => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
                                    'confirmed'   => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
                                    'cancelled'   => 'bg-gray-200 text-gray-700 dark:bg-gray-900/40 dark:text-gray-200',
                                    'checked_in'  => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                                    'checked_out' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200',
                                ];

                                $statusClass = $statusMap[$b->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/40 dark:text-gray-200';
                            @endphp

                            <td class="py-3">
                                <span class="px-2 py-1 rounded text-sm {{ $statusClass }}">
                                    {{ __(strtoupper($b->status)) }}
                                </span>
                            </td>

                                <td class="py-3 text-gray-800 dark:text-gray-200">
                                    №{{ $b->room->number ?? '-' }}
                                    @if($b->room?->roomType)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $b->room->roomType->name }}
                                        </div>
                                    @endif
                                </td>

                                <td class="py-3 text-gray-800 dark:text-gray-200">
                                    {{ optional($b->date_from)->format('d.m.Y') }}
                                    -
                                    {{ optional($b->date_to)->format('d.m.Y') }}
                                </td>

                                <td class="py-3">
                                    <span class="px-2 py-1 rounded text-sm {{ $statusClass }}">
                                        {{ __(strtoupper($b->status)) }}
                                    </span>
                                </td>

                                <td class="py-3 text-gray-800 dark:text-gray-200">
                                    {{ number_format((float)$b->total, 2, '.', ' ') }} {{ __('лей') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('У вас пока нет заявок.') }}<br>
                                    {{ __('Нажмите «Подать заявку», чтобы создать первую.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>