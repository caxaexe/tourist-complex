<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Мои заявки
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">

        <a href="{{ route('my.bookings.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
            Подать заявку
        </a>

        @if(session('success'))
            <div class="mt-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-4 bg-white dark:bg-gray-800 shadow rounded p-4 text-gray-800 dark:text-gray-200">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left py-2">ID</th>
                        <th class="text-left py-2">Номер</th>
                        <th class="text-left py-2">Даты</th>
                        <th class="text-left py-2">Статус</th>
                        <th class="text-left py-2">Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="py-2">{{ $b->id }}</td>
                            <td class="py-2">№{{ $b->room->number }}</td>
                            <td class="py-2">{{ $b->date_from }} — {{ $b->date_to }}</td>
                            <td class="py-2">{{ $b->status }}</td>
                            <td class="py-2">{{ number_format($b->total,2,'.',' ') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4">Заявок нет</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>