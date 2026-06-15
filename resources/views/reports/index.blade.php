<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Отчёты') }}
            </h2>
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap gap-2 items-center">
                <input type="date" name="from" value="{{ $from->toDateString() }}" class="border rounded px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                <input type="date" name="to" value="{{ $to->toDateString() }}" class="border rounded px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">{{ __('Применить') }}</button>
            </form>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Выручка за последние 7 дней') }}</h3>
                <canvas id="revenueChart" height="80"></canvas>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                    <div class="text-sm text-gray-500">{{ __('Доход по оплатам') }}</div>
                    <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ number_format($paymentsTotal, 2, '.', ' ') }}</div>
                    <div class="text-xs text-gray-500">{{ __('Кол-во оплат:') }} {{ $paymentsCount }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                    <div class="text-sm text-gray-500">{{ __('Сумма выставленных счетов') }}</div>
                    <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ number_format($invoicesTotal, 2, '.', ' ') }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                    <div class="text-sm text-gray-500">{{ __('Период') }}</div>
                    <div class="text-gray-800 dark:text-gray-200 font-semibold">{{ $from->format('d.m.Y') }} — {{ $to->format('d.m.Y') }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">{{ __('Счета по статусам') }}</h3>
                    <table class="w-full text-sm text-gray-200">
                        @foreach($invoicesByStatus as $row)
                            <tr class="border-b border-gray-700">
                                <td class="py-2">{{ $row->status }}</td>
                                <td>{{ $row->cnt }}</td>
                                <td>{{ number_format($row->sum_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">{{ __('Топ услуг') }}</h3>
                    <table class="w-full text-sm text-gray-200">
                        @foreach($topServices as $s)
                            <tr class="border-b border-gray-700">
                                <td class="py-2">{{ $s->name }}</td>
                                <td>{{ $s->qty }}</td>
                                <td>{{ number_format($s->revenue, 2) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->keys()) !!},
                datasets: [{
                    label: 'Выручка (леи)',
                    data: {!! json_encode($chartData->values()) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { responsive: true }
        });
    </script>
</x-app-layout>