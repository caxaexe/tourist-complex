@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 dark:text-gray-200 leading-tight">
            Добавить клиента
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                <form method="POST" action="{{ route($prefix.'clients.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200 dark:text-gray-200">ФИО *</label>
                        <input name="full_name"
                               value="{{ old('full_name') }}"
                               class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('full_name') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200 dark:text-gray-200">Телефон</label>
                        <input name="phone"
                               value="{{ old('phone') }}"
                               class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('phone') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200 dark:text-gray-200">Email</label>
                        <input name="email"
                               value="{{ old('email') }}"
                               class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('email') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block mb-1 text-gray-700 dark:text-gray-200 dark:text-gray-200">Серия паспорта</label>
                            <input name="passport_series"
                                   value="{{ old('passport_series') }}"
                                   class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('passport_series') <div class="text-red-600">{{ $message }}</div> @enderror
                        </div>
                        <div class="w-1/2">
                            <label class="block mb-1 text-gray-700 dark:text-gray-200 dark:text-gray-200">Номер паспорта</label>
                            <input name="passport_number"
                                   value="{{ old('passport_number') }}"
                                   class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('passport_number') <div class="text-red-600">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200 dark:text-gray-200">Дата рождения</label>
                        <input type="date"
                               name="birth_date"
                               value="{{ old('birth_date') }}"
                               class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('birth_date') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200 dark:text-gray-200">Адрес</label>
                        <textarea name="address"
                                  class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  rows="3">{{ old('address') }}</textarea>
                        @error('address') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                            Сохранить
                        </button>
                        <a href="{{ route($prefix.'clients.index') }}"
                           class="px-4 py-2 border rounded text-gray-700 dark:text-gray-200 dark:text-gray-200">
                            Назад
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
