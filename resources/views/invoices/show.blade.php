<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Счёт {{ $invoice->number }}
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded">
                    + Добавить оплату
                </a>

                <a href="{{ route('invoices.index') }}"
                   class="px-4 py-2 border rounded text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                    Назад
                </a>
            </div>
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
                        <div class="text-sm text-gray-500 dark:text-gray-400">Клиент</div>
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ $invoice->booking->client->full_name ?? '—' }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Бронирование #{{ $invoice->booking_id }}
                        </div>
                    </div>

                    <div class="sm:text-right">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Дата выставления</div>
                        <div class="text-gray-800 dark:text-gray-200">
                            {{ optional($invoice->issued_at)->format('d.m.Y') }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Оплатить до</div>
                        <div class="text-gray-800 dark:text-gray-200">
                            {{ optional($invoice->due_at)->format('d.m.Y') }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Статус</div>
                        <div class="text-gray-800 dark:text-gray-200">
                            {{ $invoice->status }}
                        </div>
                    </div>
                </div>

                <hr class="my-5 border-gray-200 dark:border-gray-700">

                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200">
                            <th class="py-2">Позиция</th>
                            <th class="text-right">Кол-во</th>
                            <th class="text-right">Цена</th>
                            <th class="text-right">Сумма</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-200">
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
                    <div class="text-sm text-gray-500 dark:text-gray-400">Итого</div>
                    <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ number_format($invoice->total, 2, '.', ' ') }}
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                @php
                    $paid = (float)$invoice->payments->sum('amount');
                    $due  = (float)$invoice->total;
                    $balance = max(0, $due - $paid);

                    if ($paid <= 0) { $payText='UNPAID'; $payCls='bg-red-100 text-red-800'; }
                    elseif ($paid + 0.01 < $due) { $payText='PARTIAL'; $payCls='bg-yellow-100 text-yellow-800'; }
                    else { $payText='PAID'; $payCls='bg-green-100 text-green-800'; }
                @endphp

                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            Оплаты по счету
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Оплачено: <b>{{ number_format($paid,2,'.',' ') }}</b> |
                            Остаток: <b>{{ number_format($balance,2,'.',' ') }}</b>
                        </div>
                    </div>

                    <span class="px-2 py-1 rounded text-sm {{ $payCls }}">{{ $payText }}</span>
                </div>

                <div class="mt-4 bg-white dark:bg-gray-800 shadow rounded p-4">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200">
                                <th class="py-2">ID</th>
                                <th>Сумма</th>
                                <th>Метод</th>
                                <th>Дата</th>
                                <th>Комментарий</th>
                                <th class="text-right">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 dark:text-gray-200">
                            @forelse($invoice->payments as $p)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2">{{ $p->id }}</td>
                                    <td>{{ number_format($p->amount,2,'.',' ') }}</td>
                                    <td>{{ $p->method }}</td>
                                    <td>{{ optional($p->paid_at)->format('d.m.Y H:i') }}</td>
                                    <td class="text-sm text-gray-500 dark:text-gray-400">{{ $p->note ?? '—' }}</td>
                                    <td class="text-right">
                                        <form method="POST" action="{{ route('payments.destroy', $p) }}"
                                              onsubmit="return confirm('Удалить оплату?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600">Удалить</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                        Оплат нет
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
