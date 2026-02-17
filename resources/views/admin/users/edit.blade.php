<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Редактировать: {{ $user->full_name ?? $user->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded p-6">

            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block mb-1">Логин (name)*</label>
                    <input name="name" value="{{ old('name', $user->name) }}" class="border rounded w-full px-3 py-2">
                    @error('name') <div class="text-red-600">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block mb-1">ФИО</label>
                    <input name="full_name" value="{{ old('full_name', $user->full_name) }}" class="border rounded w-full px-3 py-2">
                </div>

                <div>
                    <label class="block mb-1">Email*</label>
                    <input name="email" value="{{ old('email', $user->email) }}" class="border rounded w-full px-3 py-2">
                    @error('email') <div class="text-red-600">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block mb-1">Телефон</label>
                    <input name="phone" value="{{ old('phone', $user->phone) }}" class="border rounded w-full px-3 py-2">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block mb-1">Новый пароль (если нужно)</label>
                        <input type="password" name="password" class="border rounded w-full px-3 py-2">
                        @error('password') <div class="text-red-600">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block mb-1">Повтор пароля</label>
                        <input type="password" name="password_confirmation" class="border rounded w-full px-3 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block mb-1">Должность</label>
                        <input name="position" value="{{ old('position', $user->position) }}" class="border rounded w-full px-3 py-2">
                    </div>
                    <div>
                        <label class="block mb-1">Зарплата</label>
                        <input type="number" step="0.01" name="salary" value="{{ old('salary', $user->salary) }}"
                               class="border rounded w-full px-3 py-2">
                    </div>
                </div>

                <div>
                    <label class="block mb-1">Обязанности</label>
                    <textarea name="duties" rows="3" class="border rounded w-full px-3 py-2">{{ old('duties', $user->duties) }}</textarea>
                </div>

                <div>
                    <label class="block mb-2">Роли</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($roles as $role)
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                       @checked(in_array($role->id, old('roles', $selectedRoleIds)))>
                                <span>{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active))>
                        <span>Активен</span>
                    </label>
                </div>

                <div class="flex gap-3">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded">Сохранить</button>
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border rounded">Назад</a>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
