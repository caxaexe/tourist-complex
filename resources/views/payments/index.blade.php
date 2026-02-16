<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Оплаты
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
                <a href="{{ route('payments.create') }}"
                   class="sm:ml-auto px-4 py-2 bg-blue-600 text-white rounded">
                    + Добавить оплату
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 text-gray-700 dark:text-gray-200">ID</th>
                            <th class="text-gray-700 dark:text-gray-200">Бронь</th>
                            <th class="text-gray-700 dark:text-gray-200">Клиент</th>
                            <th class="text-gray-700 dark:text-gray-200">Сумма</th>
                            <th class="text-gray-700 dark:text-gray-200">Метод</th>
                            <th class="text-gray-700 dark:text-gray-200">Дата</th>
                            <th class="text-right text-gray-700 dark:text-gray-200">Действия</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($payments as $p)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2 text-gray-800 dark:text-gray-200">
                                    {{ $p->id }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    #{{ $p->booking_id }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ $p->booking->client->full_name ?? '—' }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ number_format($p->amount, 2, '.', ' ') }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ $p->method }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ optional($p->paid_at)->format('d.m.Y H:i') }}
                                </td>

                                <td class="text-right">
                                    <form class="inline"
                                          method="POST"
                                          action="{{ route('payments.destroy', $p) }}"
                                          onsubmit="return confirm('Удалить оплату?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600">
                                            Удалить
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-500">
                                    Нет оплат
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $payments->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
