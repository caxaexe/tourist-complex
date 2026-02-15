<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Счета (Invoices)
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2">№</th>
                            <th>Бронь</th>
                            <th>Клиент</th>
                            <th>Дата</th>
                            <th>Статус</th>
                            <th>Сумма</th>
                            <th class="text-right">Открыть</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($invoices as $inv)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2">{{ $inv->number }}</td>
                                <td>#{{ $inv->booking_id }}</td>
                                <td>{{ $inv->booking->client->full_name ?? '—' }}</td>
                                <td>{{ optional($inv->issued_at)->format('d.m.Y') }}</td>
                                <td>{{ $inv->status }}</td>
                                <td>{{ number_format($inv->total, 2, '.', ' ') }}</td>
                                <td class="text-right">
                                    <a class="text-blue-600" href="{{ route('invoices.show', $inv) }}">
                                        Просмотр
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-500">Нет счетов</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $invoices->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
