<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Добавить оплату
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                <form method="POST" action="{{ route('payments.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-1">Бронирование *</label>
                        <select name="booking_id" class="border rounded w-full px-3 py-2">
                            <option value="">— выбрать —</option>
                            @foreach($bookings as $b)
                                <option value="{{ $b->id }}"
                                    @selected(old('booking_id', $bookingId) == $b->id)>
                                    #{{ $b->id }} — {{ $b->client->full_name ?? '—' }}
                                </option>
                            @endforeach
                        </select>
                        @error('booking_id') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1">Сумма *</label>
                        <input type="number" step="0.01" min="0.01"
                               name="amount" value="{{ old('amount') }}"
                               class="border rounded w-full px-3 py-2">
                        @error('amount') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1">Метод *</label>
                        <select name="method" class="border rounded w-full px-3 py-2">
                            @foreach(['cash','card','transfer'] as $m)
                                <option value="{{ $m }}" @selected(old('method') == $m)>{{ $m }}</option>
                            @endforeach
                        </select>
                        @error('method') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1">Дата оплаты</label>
                        <input type="datetime-local"
                               name="paid_at"
                               value="{{ old('paid_at') }}"
                               class="border rounded w-full px-3 py-2">
                        @error('paid_at') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1">Комментарий</label>
                        <textarea name="note" rows="3"
                                  class="border rounded w-full px-3 py-2">{{ old('note') }}</textarea>
                        @error('note') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">Сохранить</button>
                        <a href="{{ route('payments.index') }}" class="px-4 py-2 border rounded">Назад</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
