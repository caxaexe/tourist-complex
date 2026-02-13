<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Редактировать тип номера #{{ $room_type->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
                <form method="POST" action="{{ route('room-types.update', $room_type) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">Название *</label>
                        <input name="name" value="{{ old('name', $room_type->name) }}"
                               class="border rounded w-full px-3 py-2">
                        @error('name') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">Описание</label>
                        <textarea name="description"
                                  class="border rounded w-full px-3 py-2"
                                  rows="4">{{ old('description', $room_type->description) }}</textarea>
                        @error('description') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">
                            Сохранить
                        </button>
                        <a href="{{ route('room-types.index') }}"
                           class="px-4 py-2 border rounded text-gray-700 dark:text-gray-200">
                            Назад
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
