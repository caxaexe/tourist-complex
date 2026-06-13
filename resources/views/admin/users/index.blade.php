<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                {{ __('Персонал') }}
            </h2>

            <div class="flex gap-2">
                <form method="GET" class="flex gap-2">
                    <input name="q" value="{{ $q }}" placeholder="{{ __('Поиск...') }}"
                           class="border border-gray-300 dark:border-gray-700 rounded px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button class="px-3 py-2 border rounded">{{ __('Найти') }}</button>
                </form>

                <a href="{{ route('admin.users.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    {{ __('+ Добавить') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded text-gray-800 dark:text-gray-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow rounded p-4 overflow-auto text-gray-800 dark:text-gray-200">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">ID</th>
                        <th>{{ __('Имя') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Роли') }}</th>
                        <th>{{ __('Должность') }}</th>
                        <th>{{ __('ЗП') }}</th>
                        <th>{{ __('Активен') }}</th>
                        <th class="text-right">{{ __('Действия') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $u)
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="py-2">{{ $u->id }}</td>
                        <td>
                            {{ $u->full_name ?? $u->name }}
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $u->name }}</div>
                        </td>
                        <td>{{ $u->email }}</td>
                        <td class="text-sm">
                            {{ $u->roles->pluck('name')->join(', ') ?: '—' }}
                        </td>
                        <td>{{ $u->position ?? '—' }}</td>
                        <td>{{ $u->salary !== null ? number_format($u->salary,2,'.',' ') : '—' }}</td>
                        <td>
                            @if($u->is_active)
                                <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-sm">{{ __('да') }}</span>
                            @else
                                <span class="px-2 py-1 rounded bg-gray-200 text-gray-800 dark:text-gray-200 text-sm">{{ __('нет') }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a class="text-blue-600" href="{{ route('admin.users.edit', $u) }}">{{ __('Редактировать') }}</a>

                            <form class="inline"
                                  method="POST"
                                  action="{{ route('admin.users.destroy', $u) }}"
                                  onsubmit="return confirm('{{ __('Удалить пользователя?') }}')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 ml-3">{{ __('Удалить') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-4 text-center text-gray-500 dark:text-gray-400">{{ __('Нет пользователей') }}</td></tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>