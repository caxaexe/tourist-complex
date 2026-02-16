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

            <div class="mb-4 flex flex-col sm:flex-row gap-2 sm:items-center">
                <a href="{{ route('bookings.create') }}"
                   class="sm:ml-auto px-4 py-2 bg-blue-600 text-white rounded">
                    + Создать бронирование
                </a>
            </div>

            <div class="mb-4 flex flex-wrap items-center gap-2">
                <a href="{{ route('bookings.index') }}"
                   class="px-3 py-2 rounded border border-gray-200 dark:border-gray-700
                          {{ empty($payment) ? 'bg-gray-100 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }}
                          text-gray-800 dark:text-gray-200">
                    Все
                </a>

                <a href="{{ route('bookings.index', ['payment' => 'unpaid']) }}"
                   class="px-3 py-2 rounded border border-gray-200 dark:border-gray-700
                          {{ ($payment ?? '') === 'unpaid' ? 'bg-gray-100 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }}
                          text-gray-800 dark:text-gray-200">
                    UNPAID
                </a>

                <a href="{{ route('bookings.index', ['payment' => 'partial']) }}"
                   class="px-3 py-2 rounded border border-gray-200 dark:border-gray-700
                          {{ ($payment ?? '') === 'partial' ? 'bg-gray-100 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }}
                          text-gray-800 dark:text-gray-200">
                    PARTIAL
                </a>

                <a href="{{ route('bookings.index', ['payment' => 'paid']) }}"
                   class="px-3 py-2 rounded border border-gray-200 dark:border-gray-700
                          {{ ($payment ?? '') === 'paid' ? 'bg-gray-100 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }}
                          text-gray-800 dark:text-gray-200">
                    PAID
                </a>
            </div>

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
                            <th class="py-2 text-gray-700 dark:text-gray-200">ID</th>
                            <th class="text-gray-700 dark:text-gray-200">Клиент</th>
                            <th class="text-gray-700 dark:text-gray-200">Номер</th>
                            <th class="text-gray-700 dark:text-gray-200">Даты</th>
                            <th class="text-gray-700 dark:text-gray-200">Ночей</th>
                            <th class="text-gray-700 dark:text-gray-200">Цена/ночь</th>
                            <th class="text-gray-700 dark:text-gray-200">Статус</th>
                            <th class="text-gray-700 dark:text-gray-200">Оплата</th>
                            <th class="text-gray-700 dark:text-gray-200">Сумма</th>
                            <th class="text-right text-gray-700 dark:text-gray-200">Действия</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $booking)
                            @php
                                $map = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-gray-200 text-gray-700',
                                    'checked_in' => 'bg-blue-100 text-blue-800',
                                    'checked_out' => 'bg-purple-100 text-purple-800',
                                ];
                                $cls = $map[$booking->status] ?? 'bg-gray-100 text-gray-800';

                                $nights = $booking->date_from->diffInDays($booking->date_to);

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
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $booking->id }}</td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ $booking->client->full_name }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    №{{ $booking->room->number }}
                                    @if($booking->room->roomType)
                                        <div class="text-xs text-gray-500">
                                            {{ $booking->room->roomType->name }}
                                        </div>
                                    @endif
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ $booking->date_from->format('d.m.Y') }}
                                    —
                                    {{ $booking->date_to->format('d.m.Y') }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">{{ $nights }}</td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ number_format($booking->room->price_per_night, 2, '.', ' ') }}
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    <span class="px-2 py-1 rounded text-sm {{ $cls }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    <a href="{{ route('payments.create', ['booking_id' => $booking->id]) }}"
                                       class="inline-block px-2 py-1 rounded text-sm {{ $payCls }}">
                                        {{ $payText }}
                                    </a>

                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ number_format($paid, 2, '.', ' ') }} / {{ number_format($due, 2, '.', ' ') }}
                                    </div>
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ number_format($booking->total, 2, '.', ' ') }}
                                </td>

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
                                            <button class="text-red-600 ml-3">Удалить</button>
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
