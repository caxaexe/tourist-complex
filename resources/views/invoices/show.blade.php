<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Счёт {{ $invoice->number }}
                </h2>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Бронирование #{{ $invoice->booking_id }} • Клиент: {{ $invoice->booking->client->full_name ?? '—' }}
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded">
                    + Добавить оплату
                </a>

                <a href="{{ route('invoices.index') }}"
                   class="px-4 py-2 border rounded text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                    Назад
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded text-green-900">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                {{-- Верхняя инфа --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Клиент</div>
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ $invoice->booking->client->full_name ?? '—' }}
                        </div>

                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">Номер / Комната</div>
                        <div class="text-gray-800 dark:text-gray-200">
                            №{{ $invoice->booking->room->number ?? '—' }}
                            @if(!empty($invoice->booking->room->roomType))
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    ({{ $invoice->booking->room->roomType->name }})
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="sm:text-right">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Дата выставления</div>
                        <div class="text-gray-800 dark:text-gray-200">
                            {{ optional($invoice->issued_at)->format('d.m.Y') ?? '—' }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Оплатить до</div>
                        <div class="text-gray-800 dark:text-gray-200">
                            {{ optional($invoice->due_at)->format('d.m.Y') ?? '—' }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Статус счёта</div>
                        <div class="text-gray-800 dark:text-gray-200">
                            {{ $invoice->status }}
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                {{-- Позиции счета --}}
                <div class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
                    Позиции
                </div>

                <div class="overflow-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                <th class="py-2 text-gray-700 dark:text-gray-200">Позиция</th>
                                <th class="text-right text-gray-700 dark:text-gray-200">Кол-во</th>
                                <th class="text-right text-gray-700 dark:text-gray-200">Цена</th>
                                <th class="text-right text-gray-700 dark:text-gray-200">Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoice->items as $item)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 text-gray-800 dark:text-gray-200">
                                        {{ $item->title }}
                                    </td>
                                    <td class="text-right text-gray-800 dark:text-gray-200">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="text-right text-gray-800 dark:text-gray-200">
                                        {{ number_format($item->unit_price, 2, '.', ' ') }}
                                    </td>
                                    <td class="text-right text-gray-800 dark:text-gray-200">
                                        {{ number_format($item->line_total, 2, '.', ' ') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                        Позиции отсутствуют
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Итого по счёту</div>
                    <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ number_format($invoice->total, 2, '.', ' ') }}
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                {{-- ✅ Оплаты по счету --}}
                @php
                    $paid = (float) $invoice->payments->sum('amount');
                    $due  = (float) ($invoice->total ?? 0);
                    $balance = max(0, $due - $paid);

                    if ($due <= 0) {
                        $payText = 'PAID';
                        $payCls  = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200';
                    } elseif ($paid <= 0) {
                        $payText = 'UNPAID';
                        $payCls  = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200';
                    } elseif ($paid + 0.01 < $due) {
                        $payText = 'PARTIAL';
                        $payCls  = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200';
                    } else {
                        $payText = 'PAID';
                        $payCls  = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200';
                    }
                @endphp

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            Оплаты по счёту
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Оплачено: <b class="text-gray-800 dark:text-gray-200">{{ number_format($paid,2,'.',' ') }}</b>
                            • Остаток: <b class="text-gray-800 dark:text-gray-200">{{ number_format($balance,2,'.',' ') }}</b>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 rounded text-sm {{ $payCls }}">{{ $payText }}</span>

                        <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded">
                            + Добавить оплату
                        </a>
                    </div>
                </div>

                <div class="mt-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded p-4 overflow-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                <th class="py-2 text-gray-700 dark:text-gray-200">ID</th>
                                <th class="text-gray-700 dark:text-gray-200">Сумма</th>
                                <th class="text-gray-700 dark:text-gray-200">Метод</th>
                                <th class="text-gray-700 dark:text-gray-200">Дата</th>
                                <th class="text-gray-700 dark:text-gray-200">Комментарий</th>
                                <th class="text-right text-gray-700 dark:text-gray-200">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoice->payments as $p)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 text-gray-800 dark:text-gray-200">{{ $p->id }}</td>
                                    <td class="text-gray-800 dark:text-gray-200">{{ number_format($p->amount,2,'.',' ') }}</td>
                                    <td class="text-gray-800 dark:text-gray-200">{{ $p->method }}</td>
                                    <td class="text-gray-800 dark:text-gray-200">{{ optional($p->paid_at)->format('d.m.Y H:i') }}</td>
                                    <td class="text-sm text-gray-500 dark:text-gray-400">{{ $p->note ?? '—' }}</td>
                                    <td class="text-right whitespace-nowrap">
                                        <form method="POST"
                                              action="{{ route('payments.destroy', $p) }}"
                                              onsubmit="return confirm('Удалить оплату?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 dark:text-red-300">
                                                Удалить
                                            </button>
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
