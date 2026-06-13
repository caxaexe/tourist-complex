<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Отчёты') }}
            </h2>

            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap gap-2 items-center">
                <input type="date" name="from"
                       value="{{ $from->toDateString() }}"
                       class="border rounded px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">

                <input type="date" name="to"
                       value="{{ $to->toDateString() }}"
                       class="border rounded px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">

                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    {{ __('Применить') }}
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- KPI --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded p-4 text-gray-800 dark:text-gray-200">
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Доход по оплатам') }}</div>
                    <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ number_format($paymentsTotal, 2, '.', ' ') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Кол-во оплат:') }} {{ $paymentsCount }}
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded p-4 text-gray-800 dark:text-gray-200">
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Сумма выставленных счетов') }}</div>
                    <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ number_format($invoicesTotal, 2, '.', ' ') }}
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded p-4 text-gray-800 dark:text-gray-200">
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Период') }}</div>
                    <div class="text-gray-800 dark:text-gray-200 font-semibold">
                        {{ $from->format('d.m.Y') }} — {{ $to->format('d.m.Y') }}
                    </div>
                </div>
            </div>

            {{-- Счета по статусам --}}
            <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded p-4 overflow-auto text-gray-800 dark:text-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
                    {{ __('Счета по статусам') }}
                </h3>

                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200">
                            <th class="py-2">{{ __('Статус') }}</th>
                            <th>{{ __('Кол-во') }}</th>
                            <th>{{ __('Сумма') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-200">
                        @forelse($invoicesByStatus as $row)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2">{{ $row->status }}</td>
                                <td>{{ $row->cnt }}</td>
                                <td>{{ number_format($row->sum_total, 2, '.', ' ') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('Нет данных') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Топ услуг --}}
            <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded p-4 overflow-auto text-gray-800 dark:text-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
                    {{ __('Топ услуг (по выручке)') }}
                </h3>

                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200">
                            <th class="py-2">{{ __('Услуга') }}</th>
                            <th>{{ __('Кол-во') }}</th>
                            <th>{{ __('Выручка') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-200">
                        @forelse($topServices as $s)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2">{{ $s->name }}</td>
                                <td>{{ $s->qty }}</td>
                                <td>{{ number_format($s->revenue, 2, '.', ' ') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('Нет данных') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Загрузка номеров --}}
            <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded p-4 overflow-auto text-gray-800 dark:text-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
                    {{ __('Загрузка номеров (топ по ночам)') }}
                </h3>

                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200">
                            <th class="py-2">{{ __('Номер') }}</th>
                            <th>{{ __('Бронирований') }}</th>
                            <th>{{ __('Ночей') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-200">
                        @forelse($roomOccupancy as $r)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2">№{{ $r->number }}</td>
                                <td>{{ $r->bookings_count }}</td>
                                <td>{{ (int)$r->nights }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('Нет данных') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>