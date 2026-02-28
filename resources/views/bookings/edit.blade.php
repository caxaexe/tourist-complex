@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 dark:text-gray-200 leading-tight">
            Редактировать бронирование #{{ $booking->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Ошибки формы --}}
            @if($errors->any() && !$errors->has('delete') && !$errors->has('invoice'))
                <div class="mb-4 p-3 bg-red-100 border border-red-300 rounded text-red-900">
                    Проверь поля формы — есть ошибки.
                </div>
            @endif

            {{-- Ошибка удаления --}}
            @if($errors->has('delete'))
                <div class="mb-4 p-3 bg-red-100 border border-red-300 rounded text-red-900">
                    {{ $errors->first('delete') }}
                </div>
            @endif

            {{-- Ошибка создания счёта --}}
            @if($errors->has('invoice'))
                <div class="mb-4 p-3 bg-red-100 border border-red-300 rounded text-red-900">
                    {{ $errors->first('invoice') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                {{-- ФОРМА ОБНОВЛЕНИЯ --}}
                <form id="booking-update-form"
                      method="POST"
                      action="{{ route($prefix.'bookings.update', $booking) }}"
                      class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Клиент --}}
                    <div>
                        <label class="block mb-1 text-gray-800 dark:text-gray-200 dark:text-gray-200">Клиент *</label>
                        <select name="client_id"
                                class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 dark:text-gray-200 border-gray-200 dark:border-gray-700 dark:border-gray-700">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                    @selected(old('client_id', $booking->client_id) == $client->id)>
                                    {{ $client->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Номер --}}
                    <div>
                        <label class="block mb-1 text-gray-800 dark:text-gray-200 dark:text-gray-200">Номер *</label>
                        <select name="room_id"
                                class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 dark:text-gray-200 border-gray-200 dark:border-gray-700 dark:border-gray-700">
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}"
                                    @selected(old('room_id', $booking->room_id) == $room->id)>
                                    №{{ $room->number }}
                                </option>
                            @endforeach
                        </select>
                        @error('room_id') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Даты --}}
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block mb-1 text-gray-800 dark:text-gray-200 dark:text-gray-200">Дата заезда *</label>
                            <input type="date"
                                   name="date_from"
                                   value="{{ old('date_from', $booking->date_from->format('Y-m-d')) }}"
                                   class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 dark:text-gray-200 border-gray-200 dark:border-gray-700 dark:border-gray-700">
                            @error('date_from') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="w-1/2">
                            <label class="block mb-1 text-gray-800 dark:text-gray-200 dark:text-gray-200">Дата выезда *</label>
                            <input type="date"
                                   name="date_to"
                                   value="{{ old('date_to', $booking->date_to->format('Y-m-d')) }}"
                                   class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 dark:text-gray-200 border-gray-200 dark:border-gray-700 dark:border-gray-700">
                            @error('date_to') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Статус --}}
                    <div>
                        <label class="block mb-1 text-gray-800 dark:text-gray-200 dark:text-gray-200">Статус</label>
                        <select name="status"
                                class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 dark:text-gray-200 border-gray-200 dark:border-gray-700 dark:border-gray-700">
                            @foreach(['pending','confirmed','cancelled','checked_in','checked_out'] as $status)
                                <option value="{{ $status }}"
                                    @selected(old('status', $booking->status) == $status)>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Примечание --}}
                    <div>
                        <label class="block mb-1 text-gray-800 dark:text-gray-200 dark:text-gray-200">Примечание</label>
                        <textarea name="note"
                                  rows="3"
                                  class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 dark:text-gray-200 border-gray-200 dark:border-gray-700 dark:border-gray-700">{{ old('note', $booking->note) }}</textarea>
                        @error('note') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                </form>

                <hr class="my-6">

                {{-- КНОПКИ --}}
                <div class="flex flex-wrap gap-3">

                    <button form="booking-update-form"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        Сохранить
                    </button>

                    <a href="{{ route($prefix.'bookings.index') }}"
                       class="px-4 py-2 border rounded text-gray-800 dark:text-gray-200 dark:text-gray-200 border-gray-200 dark:border-gray-700 dark:border-gray-700">
                        Назад
                    </a>

                    {{-- Удаление --}}
                    <form method="POST"
                          action="{{ route($prefix.'bookings.destroy', $booking) }}"
                          onsubmit="return confirm('ТОЧНО удалить бронирование #{{ $booking->id }}?')">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                            Удалить бронирование
                        </button>
                    </form>

                    {{-- Check-in --}}
                    @if($booking->status === 'confirmed')
                        <form method="POST" action="{{ route($prefix.'bookings.checkin', $booking) }}">
                            @csrf
                            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                                Check-in
                            </button>
                        </form>
                    @endif

                    {{-- Check-out --}}
                    @if($booking->status === 'checked_in')
                        <form method="POST" action="{{ route($prefix.'bookings.checkout', $booking) }}">
                            @csrf
                            <button class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                                Check-out
                            </button>
                        </form>
                    @endif

                </div>

                <hr class="my-6">

                {{-- Создать счёт --}}
                <form method="POST"
                      action="{{ route($prefix.'bookings.invoices.store', $booking) }}">
                    @csrf
                    <button class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-black hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        Создать счёт (Invoice)
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>