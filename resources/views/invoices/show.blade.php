<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Счёт {{ $invoice->number }}
            </h2>

            <a href="{{ route('invoices.index') }}" class="px-4 py-2 border rounded">
                Назад
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-500">Клиент</div>
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ $invoice->booking->client->full_name ?? '—' }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Бронирование #{{ $invoice->booking_id }}
                        </div>
                    </div>

                    <div class="sm:text-right">
                        <div class="text-sm text-gray-500">Дата выставления</div>
                        <div class="text-gray-800 dark:text-gray-200">
                            {{ optional($invoice->issued_at)->format('d.m.Y') }}
                        </div>

                        <div class="text-sm text-gray-500 mt-2">Оплатить до</div>
                        <div class="text-gray-800 dark:text-gray-200">
                            {{ optional($invoice->due_at)->format('d.m.Y') }}
                        </div>

                        <div class="text-sm text-gray-500 mt-2">Статус</div>
                        <div class="text-gray-800 dark:text-gray-200">
                            {{ $invoice->status }}
                        </div>
                    </div>
                </div>

                <hr class="my-5">

                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2">Позиция</th>
                            <th class="text-right">Кол-во</th>
                            <th class="text-right">Цена</th>
                            <th class="text-right">Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2">{{ $item->title }}</td>
                                <td class="text-right">{{ $item->quantity }}</td>
                                <td class="text-right">{{ number_format($item->unit_price, 2, '.', ' ') }}</td>
                                <td class="text-right">{{ number_format($item->line_total, 2, '.', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 text-right">
                    <div class="text-sm text-gray-500">Итого</div>
                    <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ number_format($invoice->total, 2, '.', ' ') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
