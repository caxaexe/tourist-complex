<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800">
                Персонал
            </h2>

            <div class="flex gap-2">
                <form method="GET" class="flex gap-2">
                    <input name="q" value="{{ $q }}" placeholder="Поиск..."
                           class="border rounded px-3 py-2">
                    <button class="px-3 py-2 border rounded">Найти</button>
                </form>

                <a href="{{ route('admin.users.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded">
                    + Добавить
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded text-gray-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded p-4 overflow-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Роли</th>
                        <th>Должность</th>
                        <th>ЗП</th>
                        <th>Активен</th>
                        <th class="text-right">Действия</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $u)
                    <tr class="border-b">
                        <td class="py-2">{{ $u->id }}</td>
                        <td>
                            {{ $u->full_name ?? $u->name }}
                            <div class="text-xs text-gray-500">{{ $u->name }}</div>
                        </td>
                        <td>{{ $u->email }}</td>
                        <td class="text-sm">
                            {{ $u->roles->pluck('name')->join(', ') ?: '—' }}
                        </td>
                        <td>{{ $u->position ?? '—' }}</td>
                        <td>{{ $u->salary !== null ? number_format($u->salary,2,'.',' ') : '—' }}</td>
                        <td>
                            @if($u->is_active)
                                <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-sm">да</span>
                            @else
                                <span class="px-2 py-1 rounded bg-gray-200 text-gray-800 text-sm">нет</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a class="text-blue-600" href="{{ route('admin.users.edit', $u) }}">Редактировать</a>

                            <form class="inline"
                                  method="POST"
                                  action="{{ route('admin.users.destroy', $u) }}"
                                  onsubmit="return confirm('Удалить пользователя?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 ml-3">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-4 text-center text-gray-500">Нет пользователей</td></tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
