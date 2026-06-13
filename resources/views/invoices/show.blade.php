@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Счёт') }} {{ $invoice->number }}
                </h2>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Бронирование #') }}{{ $invoice->booking_id }} • {{ __('Клиент:') }} {{ $invoice->booking->client->full_name ?? '—' }}
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route($prefix.'payments.create', ['invoice_id' => $invoice->id]) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    {{ __('+ Добавить оплату') }}
                </a>

                <a href="{{ route($prefix.'invoices.index') }}"
                   class="px-4 py-2 border rounded text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                    {{ __('Назад') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-800 text-green-900 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                {{-- Верхняя инфа --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Клиент') }}</div>
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ $invoice->booking->client->full_name ?? '—' }}
                        </div>

                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Номер / Комната') }}</div>
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
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Дата выставления') }}</div>
                        <div class="text-gray-800 dark:text-gray-200">
                            {{ optional($invoice->issued_at)->format('d.m.Y') ?? '—' }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ __('Оплатить до') }}</div>
                        <div class="text-gray-800 dark:text-gray-200">
                            {{ optional($invoice->due_at)->format('d.m.Y') ?? '—' }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ __('Статус счёта') }}</div>
                        <div class="text-gray-800 dark:text-gray-200">
                            {{ $invoice->status }}
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                {{-- Позиции счета --}}
                <div class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
                    {{ __('Позиции') }}
                </div>

                <div class="overflow-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                <th class="py-2 text-gray-700 dark:text-gray-200">{{ __('Позиция') }}</th>
                                <th class="text-right text-gray-700 dark:text-gray-200">{{ __('Кол-во') }}</th>
                                <th class="text-right text-gray-700 dark:text-gray-200">{{ __('Цена') }}</th>
                                <th class="text-right text-gray-700 dark:text-gray-200">{{ __('Сумма') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoice->items as $item)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 text-gray-800 dark:text-gray-200">
                                        {{ $item->description ?? $item->title ?? '—' }}
                                    </td>
                                    <td class="text-right text-gray-800 dark:text-gray-200">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="text-right text-gray-800 dark:text-gray-200">
                                        {{ number_format((float)$item->unit_price, 2, '.', ' ') }}
                                    </td>
                                    <td class="text-right text-gray-800 dark:text-gray-200">
                                        {{ number_format((float)($item->total ?? $item->line_total ?? 0), 2, '.', ' ') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                        {{ __('Позиции отсутствуют') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Итого по счёту') }}</div>
                    <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ number_format((float)$invoice->total, 2, '.', ' ') }}
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                {{-- Оплаты по счету --}}
                @php
                    $paid = (float) $invoice->payments->sum('amount');
                    $due  = (float) ($invoice->total ?? 0);
                    $balance = max(0, $due - $paid);

                    if ($due <= 0) {
                        $payText = __('НЕОПЛАЧЕНО');
                        $payCls  = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200';
                    } elseif ($paid <= 0) {
                        $payText = __('НЕОПЛАЧЕНО');
                        $payCls  = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200';
                    } elseif ($paid + 0.01 < $due) {
                        $payText = __('ЧАСТИЧНО');
                        $payCls  = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200';
                    } else {
                        $payText = __('ОПЛАЧЕНО');
                        $payCls  = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200';
                    }
                @endphp

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('Оплаты по счёту') }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Оплачено:') }} <b class="text-gray-800 dark:text-gray-200">{{ number_format($paid,2,'.',' ') }}</b>
                            • {{ __('Остаток:') }} <b class="text-gray-800 dark:text-gray-200">{{ number_format($balance,2,'.',' ') }}</b>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 rounded text-sm {{ $payCls }}">{{ $payText }}</span>

                        <a href="{{ route($prefix.'payments.create', ['invoice_id' => $invoice->id]) }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                            {{ __('+ Добавить оплату') }}
                        </a>
                    </div>
                </div>

                <div class="mt-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded p-4 overflow-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                <th class="py-2 text-gray-700 dark:text-gray-200">ID</th>
                                <th class="text-gray-700 dark:text-gray-200">{{ __('Сумма') }}</th>
                                <th class="text-gray-700 dark:text-gray-200">{{ __('Метод') }}</th>
                                <th class="text-gray-700 dark:text-gray-200">{{ __('Дата') }}</th>
                                <th class="text-gray-700 dark:text-gray-200">{{ __('Комментарий') }}</th>
                                <th class="text-right text-gray-700 dark:text-gray-200">{{ __('Действия') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoice->payments as $p)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 text-gray-800 dark:text-gray-200">{{ $p->id }}</td>
                                    <td class="text-gray-800 dark:text-gray-200">{{ number_format((float)$p->amount,2,'.',' ') }}</td>
                                    <td class="text-gray-800 dark:text-gray-200">{{ $p->method }}</td>
                                    <td class="text-gray-800 dark:text-gray-200">{{ optional($p->paid_at)->format('d.m.Y H:i') }}</td>
                                    <td class="text-sm text-gray-500 dark:text-gray-400">{{ $p->note ?? '—' }}</td>
                                    <td class="text-right whitespace-nowrap">
                                        <form method="POST"
                                              action="{{ route($prefix.'payments.destroy', $p) }}"
                                              onsubmit="return confirm('{{ __('Удалить оплату?') }}')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 dark:text-red-300">
                                                {{ __('Удалить') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                        {{ __('Оплат нет') }}
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