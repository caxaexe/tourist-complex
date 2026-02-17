<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Добавить оплату
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                @if(!empty($invoice))
                    <div class="mb-4 p-3 bg-gray-100 dark:bg-gray-900/40 rounded text-gray-800 dark:text-gray-200">
                        Оплата по счёту: <b>{{ $invoice->number }}</b><br>
                        Бронирование: #{{ $invoice->booking_id }}<br>
                        Клиент: {{ $invoice->booking->client->full_name ?? '—' }}
                    </div>
                @endif

                <form method="POST" action="{{ route('payments.store') }}" class="space-y-4">
                    @csrf

                    <input type="hidden" name="invoice_id" value="{{ $invoiceId }}">

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">Сумма *</label>
                        <input type="number" step="0.01" min="0.01"
                               name="amount" value="{{ old('amount') }}"
                               class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                        @error('amount') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">Метод *</label>
                        <select name="method"
                                class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                            @foreach(['cash'=>'cash','card'=>'card','transfer'=>'transfer'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('method', 'cash') == $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('method') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">Дата оплаты</label>
                        <input type="datetime-local"
                               name="paid_at"
                               value="{{ old('paid_at') }}"
                               class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                        @error('paid_at') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">Комментарий</label>
                        <textarea name="note" rows="3"
                                  class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">{{ old('note') }}</textarea>
                        @error('note') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    @error('invoice_id') <div class="text-red-600">{{ $message }}</div> @enderror

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">Сохранить</button>

                        @if(!empty($invoiceId))
                            <a href="{{ route('invoices.show', $invoiceId) }}"
                               class="px-4 py-2 border rounded text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                                Назад к счёту
                            </a>
                        @else
                            <a href="{{ route('invoices.index') }}"
                               class="px-4 py-2 border rounded text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                                Назад
                            </a>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
