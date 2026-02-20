@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Клиенты
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex flex-col sm:flex-row gap-2 sm:items-center">
                <form method="GET" action="{{ route($prefix.'clients.index') }}" class="flex gap-2">
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Поиск (ФИО/телефон/email)"
                           class="border rounded px-3 py-2 w-80">
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">Найти</button>
                </form>

                <a href="{{ route($prefix.'clients.create') }}"
                   class="sm:ml-auto px-4 py-2 bg-blue-600 text-white rounded">
                    + Добавить клиента
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 text-gray-700 dark:text-gray-200">ID</th>
                            <th class="text-gray-700 dark:text-gray-200">ФИО</th>
                            <th class="text-gray-700 dark:text-gray-200">Телефон</th>
                            <th class="text-gray-700 dark:text-gray-200">Email</th>
                            <th class="text-right text-gray-700 dark:text-gray-200">Действия</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($clients as $client)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $client->id }}</td>
                                <td class="text-gray-800 dark:text-gray-200">{{ $client->full_name }}</td>
                                <td class="text-gray-800 dark:text-gray-200">{{ $client->phone ?? '—' }}</td>
                                <td class="text-gray-800 dark:text-gray-200">{{ $client->email ?? '—' }}</td>
                                <td class="text-right whitespace-nowrap">
                                    <a class="text-blue-600"
                                       href="{{ route($prefix.'clients.edit', $client) }}">
                                        Редактировать
                                    </a>

                                    <form class="inline"
                                          method="POST"
                                          action="{{ route($prefix.'clients.destroy', $client) }}"
                                          onsubmit="return confirm('Удалить клиента?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 ml-3">
                                            Удалить
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-gray-500">
                                    Нет клиентов
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $clients->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
