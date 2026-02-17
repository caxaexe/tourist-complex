<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Мои заявки
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">

        <a href="{{ route('my.bookings.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded">
            Подать заявку
        </a>

        <div class="mt-4 bg-white shadow rounded p-4">
            <table class="w-full">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Номер</th>
                        <th>Даты</th>
                        <th>Статус</th>
                        <th>Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr>
                            <td>{{ $b->id }}</td>
                            <td>№{{ $b->room->number }}</td>
                            <td>{{ $b->date_from }} — {{ $b->date_to }}</td>
                            <td>{{ $b->status }}</td>
                            <td>{{ number_format($b->total,2,'.',' ') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Заявок нет</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
