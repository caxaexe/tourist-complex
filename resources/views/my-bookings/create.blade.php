<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Подать заявку на бронирование') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                {{-- Форма с атрибутом данных для JS --}}
                <form method="POST" action="{{ route('my.bookings.store') }}" 
                      id="booking-form" 
                      data-disabled='@json($disabledRanges ?? [])' 
                      class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ __('ФИО') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                               class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                        @error('full_name')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ __('Телефон') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                   class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                            @error('phone')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ __('Email') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                            @error('email')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ __('Номер *') }}
                        </label>
                        <select name="room_id" required
                                class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>
                                    {{ $room->title ?: __('Комната №') . $room->number }}
                                    @if($room->roomType) - {{ $room->roomType->name }} @endif
                                    @if(isset($room->price_per_night)) ({{ number_format((float)$room->price_per_night, 2) }} {{ __('лей/ночь') }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('room_id')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ __('Дата заезда *') }}
                            </label>
                            <input type="date" name="date_from" value="{{ old('date_from') }}" required
                                   class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                            @error('date_from')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ __('Дата выезда *') }}
                            </label>
                            <input type="date" name="date_to" value="{{ old('date_to') }}" required
                                   class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                            @error('date_to')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ __('Комментарий') }}
                        </label>
                        <textarea name="note" rows="4"
                                  class="mt-1 w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="{{ __('Например: поздний заезд...') }}">{{ old('note') }}</textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-end pt-2">
                        <a href="{{ route('my.bookings.index') }}"
                           class="inline-flex justify-center px-4 py-2 rounded border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            {{ __('Отмена') }}
                        </a>
                        <button type="submit"
                                class="inline-flex justify-center px-5 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                            {{ __('Отправить заявку') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>