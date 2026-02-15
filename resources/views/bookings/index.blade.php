<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Бронирования
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('bookings.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded">
                + Создать бронирование
            </a>

            {{-- Мини-статистика --}}
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Активные</div>
                    <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ $activeCount }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Заезд сегодня</div>
                    <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ $checkInToday }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Выезд сегодня</div>
                    <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ $checkOutToday }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Подтверждено</div>
                    <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ $confirmedCount }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Сумма всего</div>
                    <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ number_format($sumTotal, 2, '.', ' ') }}
                    </div>
                </div>
            </div>

            {{-- Таблица --}}
            <div class="mt-4 bg-white dark:bg-gray-800 shadow rounded p-4">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2">ID</th>
                            <th>Клиент</th>
                            <th>Номер</th>
                            <th>Даты</th>
                            <th>Ночей</th>
                            <th>Цена/ночь</th>
                            <th>Статус</th>
                            <th>Оплата</th>
                            <th>Сумма</th>
                            <th class="text-right">Действия</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $booking)
                            @php
                                // статус бронирования
                                $map = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-gray-200 text-gray-700',
                                    'checked_in' => 'bg-blue-100 text-blue-800',
                                    'checked_out' => 'bg-purple-100 text-purple-800',
                                ];
                                $cls = $map[$booking->status] ?? 'bg-gray-100 text-gray-800';

                                $nights = $booking->date_from->diffInDays($booking->date_to);

                                // оплата
                                $paid = (float) ($booking->payments_sum_amount ?? 0);
                                $due  = (float) ($booking->total ?? 0);

                                if ($paid <= 0) {
                                    $payText = 'UNPAID';
                                    $payCls  = 'bg-red-100 text-red-800';
                                } elseif ($paid + 0.01 < $due) {
                                    $payText = 'PARTIAL';
                                    $payCls  = 'bg-yellow-100 text-yellow-800';
                                } else {
                                    $payText = 'PAID';
                                    $payCls  = 'bg-green-100 text-green-800';
                                }
                            @endphp

                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2">{{ $booking->id }}</td>

                                <td>{{ $booking->client->full_name }}</td>

                                <td>
                                    №{{ $booking->room->number }}
                                    @if($booking->room->roomType)
                                        <div class="text-xs text-gray-500">
                                            {{ $booking->room->roomType->name }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $booking->date_from->format('d.m.Y') }}
                                    —
                                    {{ $booking->date_to->format('d.m.Y') }}
                                </td>

                                <td>{{ $nights }}</td>

                                <td>{{ number_format($booking->room->price_per_night, 2, '.', ' ') }}</td>

                                <td>
                                    <span class="px-2 py-1 rounded text-sm {{ $cls }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('payments.create', ['booking_id' => $booking->id]) }}"
                                       class="inline-block px-2 py-1 rounded text-sm {{ $payCls }}">
                                        {{ $payText }}
                                    </a>

                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ number_format($paid, 2, '.', ' ') }} / {{ number_format($due, 2, '.', ' ') }}
                                    </div>
                                </td>

                                <td>{{ number_format($booking->total, 2, '.', ' ') }}</td>

                                <td class="text-right">
                                    <a class="text-blue-600"
                                       href="{{ route('bookings.edit', $booking) }}">
                                        Редактировать
                                    </a>

                                    @if($booking->status !== 'confirmed')
                                        <form class="inline"
                                              method="POST"
                                              action="{{ route('bookings.destroy', $booking) }}"
                                              onsubmit="return confirm('Удалить бронирование?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 ml-3">
                                                Удалить
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="10" class="py-4 text-center text-gray-500">
                                    Нет бронирований
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
