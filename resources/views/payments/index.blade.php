@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Оплаты
            </h2>

            {{--  Оплаты создаём только из счета --}}
            <a href="{{ route($prefix.'invoices.index') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded">
                + Добавить оплату (через счёт)
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded text-green-900">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded p-4 overflow-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 text-gray-700 dark:text-gray-200">ID</th>
                            <th class="text-gray-700 dark:text-gray-200">Счёт</th>
                            <th class="text-gray-700 dark:text-gray-200">Бронь</th>
                            <th class="text-gray-700 dark:text-gray-200">Клиент</th>
                            <th class="text-gray-700 dark:text-gray-200">Сумма</th>
                            <th class="text-gray-700 dark:text-gray-200">Метод</th>
                            <th class="text-gray-700 dark:text-gray-200">Дата</th>
                            <th class="text-right text-gray-700 dark:text-gray-200">Действия</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($payments as $p)
                            @php
                                $invoice = $p->invoice ?? null;
                                $booking = $invoice?->booking ?? null;
                                $client  = $booking?->client ?? null;
                            @endphp

                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2 text-gray-800 dark:text-gray-200">
                                    {{ $p->id }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    @if($invoice)
                                        <a href="{{ route($prefix.'invoices.show', $invoice) }}"
                                           class="text-blue-600 dark:text-blue-300 underline">
                                            {{ $invoice->number }}
                                        </a>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            статус: {{ $invoice->status }}
                                        </div>
                                    @else
                                        <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                            нет invoice_id
                                        </span>
                                    @endif
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    @if($booking)
                                        #{{ $booking->id }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ $client?->full_name ?? '—' }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ number_format((float)$p->amount, 2, '.', ' ') }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ $p->method }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ optional($p->paid_at)->format('d.m.Y H:i') ?? '—' }}
                                </td>

                                <td class="text-right whitespace-nowrap">
                                    <form class="inline"
                                          method="POST"
                                          action="{{ route($prefix.'payments.destroy', $p) }}"
                                          onsubmit="return confirm('Удалить оплату?')">
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
                                <td colspan="8" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                    Нет оплат
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $payments->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
