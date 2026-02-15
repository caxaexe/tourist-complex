<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Тип номеров
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('room-types.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded">
            + Добавить тип
        </a>

        <div class="mt-4 bg-white shadow rounded p-4">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th>ID</th>
                        <th>Название</th>
                        <th>Описание</th>
                        <th class="text-right">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roomTypes as $type)
                        <tr class="border-b">
                            <td>{{ $type->id }}</td>
                            <td>{{ $type->name }}</td>
                            <td>{{ $type->description }}</td>
                            <td class="text-right">
                                <a href="{{ route('room-types.edit', $type) }}"
                                   class="text-blue-600">Редактировать</a>

                                <form method="POST"
                                      action="{{ route('room-types.destroy', $type) }}"
                                      class="inline"
                                      onsubmit="return confirm('Удалить тип?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 ml-2">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                Нет типов номеров
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $roomTypes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
