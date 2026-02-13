<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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

            <div class="mb-4 flex gap-2 items-center">
                <form method="GET" action="{{ route('clients.index') }}" class="flex gap-2">
                    <input type="text" name="q" value="{{ $q }}" placeholder="Поиск (ФИО/телефон/email)"
                           class="border rounded px-3 py-2 w-80">
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">Найти</button>
                </form>

                <a href="{{ route('clients.create') }}"
                   class="ml-auto px-4 py-2 bg-blue-600 text-white rounded">
                    + Добавить клиента
                </a>
            </div>

            <div class="bg-white shadow rounded p-4">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2">ID</th>
                            <th>ФИО</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th class="text-right">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr class="border-b">
                                <td class="py-2">{{ $client->id }}</td>
                                <td>{{ $client->full_name }}</td>
                                <td>{{ $client->phone }}</td>
                                <td>{{ $client->email }}</td>
                                <td class="text-right">
                                    <a class="text-blue-600" href="{{ route('clients.edit', $client) }}">Редактировать</a>

                                    <form class="inline" method="POST" action="{{ route('clients.destroy', $client) }}"
                                          onsubmit="return confirm('Удалить клиента?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 ml-3">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-gray-500">Нет клиентов</td>
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
