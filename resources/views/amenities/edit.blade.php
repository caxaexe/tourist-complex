<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Редактировать удобство #{{ $amenity->id }}
        </h2>
    </x-slot>

    <div>
    <label class="block mb-2 text-gray-700 dark:text-gray-200">Удобства</label>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        @foreach($amenities as $a)
            <label class="flex items-center gap-2">
                <input type="checkbox" name="amenities[]" value="{{ $a->id }}"
                    @checked(in_array($a->id, old('amenities', $selectedAmenityIds ?? [])))>
                <span class="text-gray-700 dark:text-gray-200">{{ $a->name }}</span>
            </label>
        @endforeach
    </div>

    @error('amenities') <div class="text-red-600">{{ $message }}</div> @enderror
    @error('amenities.*') <div class="text-red-600">{{ $message }}</div> @enderror
    </div>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
                <form method="POST" action="{{ route('amenities.update', $amenity) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-1 text-gray-700 dark:text-gray-200">Название *</label>
                        <input name="name" value="{{ old('name', $amenity->name) }}" class="border rounded w-full px-3 py-2">
                        @error('name') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">Сохранить</button>
                        <a href="{{ route('amenities.index') }}" class="px-4 py-2 border rounded text-gray-700 dark:text-gray-200">Назад</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
