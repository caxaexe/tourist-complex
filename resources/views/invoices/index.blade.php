@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 dark:text-gray-200 leading-tight">
                Счета
            </h2>

            <a href="{{ route($prefix.'invoices.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                + Создать счёт
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-800 text-green-900 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 dark:bg-gray-800 shadow rounded p-4 overflow-auto text-gray-800 dark:text-gray-200">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700 dark:border-gray-700">
                            <th class="py-2 text-gray-700 dark:text-gray-200 dark:text-gray-200">ID</th>
                            <th class="text-gray-700 dark:text-gray-200 dark:text-gray-200">Номер счёта</th>
                            <th class="text-gray-700 dark:text-gray-200 dark:text-gray-200">Бронь</th>
                            <th class="text-gray-700 dark:text-gray-200 dark:text-gray-200">Клиент</th>
                            <th class="text-gray-700 dark:text-gray-200 dark:text-gray-200">Дата</th>
                            <th class="text-gray-700 dark:text-gray-200 dark:text-gray-200">Сумма</th>
                            <th class="text-gray-700 dark:text-gray-200 dark:text-gray-200">Оплата</th>
                            <th class="text-right text-gray-700 dark:text-gray-200 dark:text-gray-200">Действия</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($invoices as $invoice)
                            @php
                                $paid = (float) $invoice->payments->sum('amount');
                                $due  = (float) ($invoice->total ?? 0);
                                $balance = max(0, $due - $paid);

                                // ВАЖНО: 0 сумма НЕ означает PAID. Это просто пустой/битый счет -> UNPAID.
                                if ($due <= 0) {
                                    $payText = 'UNPAID';
                                    $payCls  = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200';
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

                            <tr class="border-b border-gray-200 dark:border-gray-700 dark:border-gray-700">
                                <td class="py-2 text-gray-800 dark:text-gray-200 dark:text-gray-200">
                                    {{ $invoice->id }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200 dark:text-gray-200">
                                    {{ $invoice->number }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200 dark:text-gray-200">
                                    #{{ $invoice->booking_id }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200 dark:text-gray-200">
                                    {{ $invoice->booking->client->full_name ?? '—' }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200 dark:text-gray-200">
                                    {{ optional($invoice->issued_at)->format('d.m.Y') ?? '—' }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200 dark:text-gray-200">
                                    {{ number_format($due, 2, '.', ' ') }}
                                </td>

                                <td>
                                    <div class="flex flex-col gap-1">
                                        <span class="px-2 py-1 rounded text-sm inline-block {{ $payCls }}">
                                            {{ $payText }}
                                        </span>

                                        <span class="text-xs text-gray-500 dark:text-gray-400 dark:text-gray-400">
                                            {{ number_format($paid,2,'.',' ') }} / {{ number_format($due,2,'.',' ') }}
                                            @if($balance > 0 && $due > 0)
                                                • Остаток: {{ number_format($balance,2,'.',' ') }}
                                            @endif
                                        </span>
                                    </div>
                                </td>

                                <td class="text-right whitespace-nowrap">
                                    <a href="{{ route($prefix.'invoices.show', $invoice) }}"
                                       class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                                        Открыть счёт
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-4 text-center text-gray-500 dark:text-gray-400 dark:text-gray-400">
                                    Счета отсутствуют
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $invoices->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
