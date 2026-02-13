<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Добавить клиента
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('clients.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-1">ФИО *</label>
                        <input name="full_name" value="{{ old('full_name') }}" class="border rounded w-full px-3 py-2">
                        @error('full_name') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1">Телефон</label>
                        <input name="phone" value="{{ old('phone') }}" class="border rounded w-full px-3 py-2">
                        @error('phone') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1">Email</label>
                        <input name="email" value="{{ old('email') }}" class="border rounded w-full px-3 py-2">
                        @error('email') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block mb-1">Серия паспорта</label>
                            <input name="passport_series" value="{{ old('passport_series') }}" class="border rounded w-full px-3 py-2">
                            @error('passport_series') <div class="text-red-600">{{ $message }}</div> @enderror
                        </div>
                        <div class="w-1/2">
                            <label class="block mb-1">Номер паспорта</label>
                            <input name="passport_number" value="{{ old('passport_number') }}" class="border rounded w-full px-3 py-2">
                            @error('passport_number') <div class="text-red-600">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1">Дата рождения</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="border rounded w-full px-3 py-2">
                        @error('birth_date') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1">Адрес</label>
                        <textarea name="address" class="border rounded w-full px-3 py-2">{{ old('address') }}</textarea>
                        @error('address') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">Сохранить</button>
                        <a href="{{ route('clients.index') }}" class="px-4 py-2 border rounded">Назад</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
