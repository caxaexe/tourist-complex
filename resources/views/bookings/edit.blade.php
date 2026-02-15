<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Редактировать бронирование #{{ $booking->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                <form method="POST"
                      action="{{ route('bookings.update', $booking) }}"
                      class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-1">Клиент *</label>
                        <select name="client_id" class="border rounded w-full px-3 py-2">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                    @selected(old('client_id', $booking->client_id) == $client->id)>
                                    {{ $client->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1">Номер *</label>
                        <select name="room_id" class="border rounded w-full px-3 py-2">
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}"
                                    @selected(old('room_id', $booking->room_id) == $room->id)>
                                    №{{ $room->number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block mb-1">Дата заезда *</label>
                            <input type="date"
                                   name="date_from"
                                   value="{{ old('date_from', $booking->date_from->format('Y-m-d')) }}"
                                   class="border rounded w-full px-3 py-2">
                        </div>

                        <div class="w-1/2">
                            <label class="block mb-1">Дата выезда *</label>
                            <input type="date"
                                   name="date_to"
                                   value="{{ old('date_to', $booking->date_to->format('Y-m-d')) }}"
                                   class="border rounded w-full px-3 py-2">
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1">Статус</label>
                        <select name="status" class="border rounded w-full px-3 py-2">
                            @foreach(['pending','confirmed','cancelled','checked_in','checked_out'] as $status)
                                <option value="{{ $status }}"
                                    @selected(old('status', $booking->status) == $status)>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1">Примечание</label>
                        <textarea name="note"
                                  class="border rounded w-full px-3 py-2"
                                  rows="3">{{ old('note', $booking->note) }}</textarea>
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
