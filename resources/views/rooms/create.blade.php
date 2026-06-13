@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Добавить номер') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
                <form method="POST" action="{{ route($prefix.'rooms.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">{{ __('Номер *') }}</label>
                        <input name="number"
                               value="{{ old('number') }}"
                               class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('number') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">{{ __('Тип номера') }}</label>
                        <select name="room_type_id" class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('— не выбран —') }}</option>
                            @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}" @selected(old('room_type_id') == $type->id)>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('room_type_id') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block mb-1 text-gray-700 dark:text-gray-200">{{ __('Цена за ночь *') }}</label>
                            <input name="price_per_night"
                                   value="{{ old('price_per_night', 0) }}"
                                   class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('price_per_night') <div class="text-red-600">{{ $message }}</div> @enderror
                        </div>
                        <div class="w-1/2">
                            <label class="block mb-1 text-gray-700 dark:text-gray-200">{{ __('Вместимость') }}</label>
                            <input name="capacity"
                                   value="{{ old('capacity') }}"
                                   class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('capacity') <div class="text-red-600">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">{{ __('Название') }}</label>
                        <input name="title"
                               value="{{ old('title') }}"
                               class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('title') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">{{ __('Описание') }}</label>
                        <textarea name="description"
                                  class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  rows="4">{{ old('description') }}</textarea>
                        @error('description') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-gray-700 dark:text-gray-200">{{ __('Удобства') }}</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($amenities as $a)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="amenities[]" value="{{ $a->id }}" @checked(in_array($a->id, old('amenities', [])))>
                                    <span class="text-gray-700 dark:text-gray-200">{{ $a->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                        <span class="text-gray-700 dark:text-gray-200">{{ __('Активен') }}</span>
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                            {{ __('Сохранить') }}
                        </button>
                        <a href="{{ route($prefix.'rooms.index') }}"
                           class="px-4 py-2 border rounded text-gray-700 dark:text-gray-200">
                            {{ __('Назад') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>