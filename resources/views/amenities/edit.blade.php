@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Редактировать удобство #') }}{{ $amenity->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 border border-red-300 rounded text-red-900">
                    {{ __('Проверь поля формы — есть ошибки.') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                <form method="POST"
                      action="{{ route($prefix.'amenities.update', $amenity) }}"
                      class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">{{ __('Название *') }}</label>
                        <input name="name"
                               value="{{ old('name', $amenity->name) }}"
                               class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900
                                      text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                        @error('name')
                            <div class="text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                            {{ __('Сохранить') }}
                        </button>

                        <a href="{{ route($prefix.'amenities.index') }}"
                           class="px-4 py-2 border rounded text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                            {{ __('Назад') }}
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>