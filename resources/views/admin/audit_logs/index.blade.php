<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Журнал действий (Audit Logs)') }}
            </h2>

            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex gap-2">
                <input name="q" value="{{ $q ?? request('q') }}"
                       class="border rounded px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700"
                       placeholder="{{ __('поиск: action, model, url, ip') }}">
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    {{ __('Найти') }}
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow rounded p-4 overflow-auto text-gray-800 dark:text-gray-200">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200">
                            <th class="py-2">ID</th>
                            <th>{{ __('Время') }}</th>
                            <th>{{ __('Пользователь') }}</th>
                            <th>{{ __('Действие') }}</th>
                            <th>{{ __('Сущность') }}</th>
                            <th>IP</th>
                            <th>URL</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-200">
                        @forelse($logs as $log)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2">{{ $log->id }}</td>
                                <td>{{ $log->created_at?->format('d.m.Y H:i') }}</td>
                                <td>{{ $log->user->name ?? '—' }}</td>
                                <td>
                                    <span class="px-2 py-1 rounded text-sm bg-gray-100 text-gray-800 dark:bg-gray-900/40 dark:text-gray-200">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="text-sm">
                                    {{ class_basename($log->entity_type) }} #{{ $log->entity_id }}
                                </td>
                                <td>{{ $log->ip }}</td>
                                <td class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $log->url }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('Записей нет') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>