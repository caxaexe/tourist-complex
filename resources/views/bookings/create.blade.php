<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Создать бронирование
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                <form method="POST" action="{{ route('bookings.store') }}" class="space-y-4">
                    @csrf

                    {{-- Клиент --}}
                    <div>
                        <label class="block mb-1">Клиент *</label>
                        <select name="client_id" class="border rounded w-full px-3 py-2">
                            <option value="">— выбрать —</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                    @selected(old('client_id') == $client->id)>
                                    {{ $client->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    {{-- Номер --}}
                    <div>
                        <label class="block mb-1">Номер *</label>
                        <select name="room_id" class="border rounded w-full px-3 py-2">
                            <option value="">— выбрать —</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}"
                                    @selected(old('room_id') == $room->id)>
                                    №{{ $room->number }}
                                    ({{ $room->roomType?->name ?? 'без типа' }})
                                </option>
                            @endforeach
                        </select>
                        @error('room_id') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    {{-- Даты --}}
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block mb-1">Дата заезда *</label>
                            <input type="date"
                                   name="date_from"
                                   value="{{ old('date_from') }}"
                                   class="border rounded w-full px-3 py-2">
                            @error('date_from') <div class="text-red-600">{{ $message }}</div> @enderror
                        </div>

                        <div class="w-1/2">
                            <label class="block mb-1">Дата выезда *</label>
                            <input type="date"
                                   name="date_to"
                                   value="{{ old('date_to') }}"
                                   class="border rounded w-full px-3 py-2">
                            @error('date_to') <div class="text-red-600">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Статус --}}
                    <div>
                        <label class="block mb-1">Статус</label>
                        <select name="status" class="border rounded w-full px-3 py-2">
                            <option value="pending">pending</option>
                            <option value="confirmed">confirmed</option>
                            <option value="cancelled">cancelled</option>
                            <option value="checked_in">checked_in</option>
                            <option value="checked_out">checked_out</option>
                        </select>
                    </div>

                    {{-- Примечание --}}
                    <div>
                        <label class="block mb-1">Примечание</label>
                        <textarea name="note"
                                  class="border rounded w-full px-3 py-2"
                                  rows="3">{{ old('note') }}</textarea>
                        @error('note') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">
                            Сохранить
                        </button>
                        <a href="{{ route('bookings.index') }}"
                           class="px-4 py-2 border rounded">
                            Назад
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
