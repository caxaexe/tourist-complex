<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Подать заявку на бронирование
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white shadow rounded p-6">

            <form method="POST" action="{{ route('my.bookings.store') }}">
                @csrf

                <div class="mb-4">
                    <label>Номер</label>
                    <select name="room_id" class="border rounded w-full p-2">
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">
                                №{{ $room->number }} — {{ $room->roomType->name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label>Дата заезда</label>
                    <input type="date" name="date_from" class="border rounded w-full p-2">
                </div>

                <div class="mb-4">
                    <label>Дата выезда</label>
                    <input type="date" name="date_to" class="border rounded w-full p-2">
                </div>

                <div class="mb-4">
                    <label>Комментарий</label>
                    <textarea name="note" class="border rounded w-full p-2"></textarea>
                </div>

                <button class="px-4 py-2 bg-green-600 text-white rounded">
                    Отправить заявку
                </button>

            </form>
        </div>
    </div>
</x-app-layout>
