<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Добавить номер
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
                <form method="POST" action="{{ route('rooms.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">Номер *</label>
                        <input name="number" value="{{ old('number') }}" class="border rounded w-full px-3 py-2">
                        @error('number') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">Тип номера</label>
                        <select name="room_type_id" class="border rounded w-full px-3 py-2">
                            <option value="">— не выбран —</option>
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
                            <label class="block mb-1 text-gray-700 dark:text-gray-200">Цена за ночь *</label>
                            <input name="price_per_night" value="{{ old('price_per_night', 0) }}" class="border rounded w-full px-3 py-2">
                            @error('price_per_night') <div class="text-red-600">{{ $message }}</div> @enderror
                        </div>
                        <div class="w-1/2">
                            <label class="block mb-1 text-gray-700 dark:text-gray-200">Вместимость</label>
                            <input name="capacity" value="{{ old('capacity') }}" class="border rounded w-full px-3 py-2">
                            @error('capacity') <div class="text-red-600">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">Название</label>
                        <input name="title" value="{{ old('title') }}" class="border rounded w-full px-3 py-2">
                        @error('title') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">Описание</label>
                        <textarea name="description" class="border rounded w-full px-3 py-2" rows="4">{{ old('description') }}</textarea>
                        @error('description') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active'))>
                        <span class="text-gray-700 dark:text-gray-200">Активен</span>
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">Сохранить</button>
                        <a href="{{ route('rooms.index') }}" class="px-4 py-2 border rounded text-gray-700 dark:text-gray-200">Назад</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
