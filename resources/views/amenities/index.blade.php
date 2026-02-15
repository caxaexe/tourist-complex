<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Удобства
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('amenities.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded">
                + Добавить удобство
            </a>

            <div class="mt-4 bg-white dark:bg-gray-800 shadow rounded p-4">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2">ID</th>
                            <th>Название</th>
                            <th class="text-right">Действия</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($amenities as $amenity)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2">{{ $amenity->id }}</td>
                                <td>{{ $amenity->name }}</td>
                                <td class="text-right">
                                    <a class="text-blue-600"
                                       href="{{ route('amenities.edit', $amenity) }}">
                                        Редактировать
                                    </a>

                                    <form class="inline"
                                          method="POST"
                                          action="{{ route('amenities.destroy', $amenity) }}"
                                          onsubmit="return confirm('Удалить удобство?')">
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
                                <td colspan="3" class="py-4 text-center text-gray-500">
                                    Нет удобств
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $amenities->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
