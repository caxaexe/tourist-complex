@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Удобства
            </h2>

            <a href="{{ route($prefix.'amenities.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                + Добавить удобство
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded text-green-900">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded p-4 overflow-auto">
                <table class="w-full">
                    <thead>
                    <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                        <th class="py-2 text-gray-700 dark:text-gray-200">ID</th>
                        <th class="text-gray-700 dark:text-gray-200">Название</th>
                        <th class="text-right text-gray-700 dark:text-gray-200">Действия</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($amenities as $amenity)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="py-2 text-gray-800 dark:text-gray-200">
                                {{ $amenity->id }}
                            </td>

                            <td class="text-gray-800 dark:text-gray-200">
                                {{ $amenity->name }}
                            </td>

                            <td class="text-right whitespace-nowrap">
                                <a href="{{ route($prefix.'amenities.edit', $amenity) }}"
                                   class="inline-block px-3 py-1 rounded border border-gray-200 dark:border-gray-700
                                          text-blue-700 dark:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                    Редактировать
                                </a>

                                <form class="inline"
                                      method="POST"
                                      action="{{ route($prefix.'amenities.destroy', $amenity) }}"
                                      onsubmit="return confirm('Удалить удобство «{{ $amenity->name }}»?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="inline-block ml-2 px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                        Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500 dark:text-gray-400">
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
