@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Добавить тип номера') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
                <form method="POST" action="{{ route($prefix.'room-types.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">{{ __('Название *') }}</label>
                        <input name="name"
                               value="{{ old('name') }}"
                               class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('name') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">{{ __('Описание') }}</label>
                        <textarea name="description"
                                  class="border border-gray-300 dark:border-gray-700 rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  rows="4">{{ old('description') }}</textarea>
                        @error('description') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                            {{ __('Сохранить') }}
                        </button>

                        <a href="{{ route($prefix.'room-types.index') }}"
                           class="px-4 py-2 border rounded text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-700">
                            {{ __('Назад') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>