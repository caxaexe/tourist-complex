<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Подать заявку на бронирование
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white shadow rounded p-6">

            <form method="POST" action="{{ route('client.my.bookings.store') }}">
                @csrf

                <div class="mb-4">
                    <label>Телефон *</label>
                    <input type="text" name="phone"
                        value="{{ old('phone') }}"
                        class="border rounded w-full p-2"
                        required>
                    @error('phone') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label>Email *</label>
                    <input type="email" name="email"
                        value="{{ old('email') }}"
                        class="border rounded w-full p-2"
                        required>
                    @error('email') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label>Номер *</label>
                    <select name="room_id" class="border rounded w-full p-2" required>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>
                                №{{ $room->number }} — {{ $room->roomType->name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_id') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label>Дата заезда *</label>
                    <input type="date" name="date_from"
                        value="{{ old('date_from') }}"
                        class="border rounded w-full p-2"
                        required>
                    @error('date_from') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label>Дата выезда *</label>
                    <input type="date" name="date_to"
                        value="{{ old('date_to') }}"
                        class="border rounded w-full p-2"
                        required>
                    @error('date_to') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label>Комментарий</label>
                    <textarea name="note" class="border rounded w-full p-2">{{ old('note') }}</textarea>
                    @error('note') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <button class="px-4 py-2 bg-green-600 text-white rounded">
                    Отправить заявку
                </button>

            </form>
        </div>
    </div>
</x-app-layout>