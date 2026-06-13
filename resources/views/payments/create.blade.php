@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Добавить оплату') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                @if(!empty($invoice))
                    <div class="mb-4 p-3 bg-gray-100 dark:bg-gray-900/40 rounded text-gray-800 dark:text-gray-200">
                        {{ __('Оплата по счёту:') }} <b>{{ $invoice->number }}</b><br>
                        {{ __('Бронирование:') }} #{{ $invoice->booking_id }}<br>
                        {{ __('Клиент:') }} {{ $invoice->booking->client->full_name ?? '—' }}
                    </div>
                @endif

                <form method="POST" action="{{ route($prefix.'payments.store') }}" class="space-y-4">
                    @csrf

                    <input type="hidden" name="invoice_id" value="{{ $invoiceId }}">

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">{{ __('Сумма *') }}</label>
                        <input type="number" step="0.01" min="0.01"
                               name="amount" value="{{ old('amount') }}"
                               class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                        @error('amount') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">{{ __('Метод *') }}</label>
                        <select name="method"
                                class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                            @foreach(['cash'=>__('Наличные'),'card'=>__('Карта'),'transfer'=>__('Перевод')] as $val => $label)
                                <option value="{{ $val }}" @selected(old('method', 'cash') == $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('method') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">{{ __('Дата оплаты') }}</label>
                        <input type="datetime-local"
                               name="paid_at"
                               value="{{ old('paid_at') }}"
                               class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                        @error('paid_at') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">{{ __('Комментарий') }}</label>
                        <textarea name="note" rows="3"
                                  class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">{{ old('note') }}</textarea>
                        @error('note') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    @error('invoice_id') <div class="text-red-600">{{ $message }}</div> @enderror

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                            {{ __('Сохранить') }}
                        </button>

                        @if(!empty($invoice))
                            <a href="{{ route($prefix.'invoices.show', $invoice) }}"
                               class="px-4 py-2 border rounded text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                                {{ __('Назад к счёту') }}
                            </a>
                        @else
                            <a href="{{ route($prefix.'invoices.index') }}"
                               class="px-4 py-2 border rounded text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                                {{ __('Назад') }}
                            </a>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>