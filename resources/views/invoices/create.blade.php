<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Создать счёт
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                <form method="POST" action="{{ route('invoices.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">Выбери бронирование *</label>

                        <select name="booking_id"
                                class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                            <option value="">— выбрать —</option>
                            @foreach($bookings as $b)
                                <option value="{{ $b->id }}" @selected(old('booking_id') == $b->id)>
                                    #{{ $b->id }} — {{ $b->client->full_name ?? '—' }}
                                    ({{ $b->date_from?->format('d.m.Y') }}–{{ $b->date_to?->format('d.m.Y') }})
                                    | status: {{ $b->status }}
                                </option>
                            @endforeach
                        </select>

                        @error('booking_id')
                            <div class="text-red-600 mt-1">{{ $message }}</div>
                        @enderror

                        @if($bookings->isEmpty())
                            <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                                Нет броней без счета (или подходящих статусов). Создай бронь / подтверди бронь — и появится здесь.
                            </div>
                        @endif
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">
                            Создать
                        </button>
                        <a href="{{ route('invoices.index') }}" class="px-4 py-2 border rounded text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                            Назад
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
