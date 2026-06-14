<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Подать заявку на бронирование') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
                
                <form method="POST" action="{{ route('my.bookings.store') }}" 
                      id="booking-form" 
                      data-disabled='@json($disabledByRoom ?? [])' 
                      class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('ФИО') }} *</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required 
                               class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Телефон') }} *</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required 
                                   class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Email') }} *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                   class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Номер *') }}</label>
                        <select name="room_id" id="room_select" required 
                                class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" @selected(old('room_id', $selectedRoomId ?? null) == $room->id)>
                                    {{ $room->title ?: __('Комната №') . $room->number }} 
                                    @if($room->roomType) - {{ $room->roomType->name }} @endif
                                    ({{ number_format((float)$room->price_per_night, 2) }} {{ __('лей/ночь') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ __('Дата заезда') }} *
                        </label>
                        <input type="text" name="date_from" id="date_from" 
                            value="{{ old('date_from') }}" 
                            required 
                            placeholder="ГГГГ-ММ-ДД"
                            class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        @error('date_from')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ __('Дата выезда') }} *
                        </label>
                        <input type="text" name="date_to" id="date_to" 
                            value="{{ old('date_to') }}" 
                            required 
                            placeholder="ГГГГ-ММ-ДД"
                            class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        @error('date_to')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('Комментарий') }}</label>
                        <textarea name="note" rows="4" 
                                  class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">{{ old('note') }}</textarea>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-5 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                            {{ __('Отправить заявку') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>