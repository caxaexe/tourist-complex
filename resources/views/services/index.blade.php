@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 dark:text-gray-200 leading-tight">
                Услуги
            </h2>

            <a href="{{ route($prefix.'services.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                + Добавить услугу
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-800 text-green-900 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 dark:bg-gray-800 shadow rounded p-4 overflow-auto text-gray-800 dark:text-gray-200">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700 dark:border-gray-700">
                            <th class="py-2 text-gray-700 dark:text-gray-200 dark:text-gray-200">ID</th>
                            <th class="text-gray-700 dark:text-gray-200 dark:text-gray-200">Название</th>
                            <th class="text-gray-700 dark:text-gray-200 dark:text-gray-200">Цена</th>
                            <th class="text-right text-gray-700 dark:text-gray-200 dark:text-gray-200">Действия</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($services as $service)
                            <tr class="border-b border-gray-200 dark:border-gray-700 dark:border-gray-700">
                                <td class="py-2 text-gray-800 dark:text-gray-200 dark:text-gray-200">
                                    {{ $service->id }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200 dark:text-gray-200">
                                    {{ $service->name }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200 dark:text-gray-200">
                                    {{ number_format($service->price, 2, '.', ' ') }}
                                </td>

                                <td class="text-right whitespace-nowrap">
                                    <a href="{{ route($prefix.'services.edit', $service) }}"
                                       class="inline-block px-3 py-1 rounded border border-gray-200 dark:border-gray-700 dark:border-gray-700
                                              text-blue-700 dark:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                        Редактировать
                                    </a>

                                    <form class="inline"
                                          method="POST"
                                          action="{{ route($prefix.'services.destroy', $service) }}"
                                          onsubmit="return confirm('Удалить услугу «{{ $service->name }}»?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline-block ml-2 px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                                            Удалить
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400 dark:text-gray-400">
                                    Нет услуг
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $services->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
