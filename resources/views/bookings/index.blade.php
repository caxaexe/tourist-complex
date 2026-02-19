<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Бронирования
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded text-green-900">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex flex-col sm:flex-row gap-2 sm:items-center">
                <a href="{{ route('bookings.create') }}"
                   class="sm:ml-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Создать бронирование
                </a>
            </div>

            {{-- Фильтр по оплате --}}
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
            <div class="mt-4 bg-white dark:bg-gray-800 shadow rounded p-4 overflow-auto">
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
                                    'pending'     => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
                                    'confirmed'   => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
                                    'cancelled'   => 'bg-gray-200 text-gray-700 dark:bg-gray-900/40 dark:text-gray-200',
                                    'checked_in'  => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                                    'checked_out' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200',
                                ];
                                $cls = $map[$booking->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/40 dark:text-gray-200';

                                $nights = max(1, $booking->date_from->diffInDays($booking->date_to));

                                $invoice = $booking->invoice ?? null;

                                // Сумма к оплате: если есть счёт — берём total счета, иначе booking->total
                                $due  = (float) ($invoice->total ?? $booking->total ?? 0);

                                // Оплачено: если есть счёт — суммируем его платежи, иначе booking->payments_sum_amount
                                $paid = (float) ($invoice ? ($invoice->payments->sum('amount') ?? 0) : ($booking->payments_sum_amount ?? 0));

                                // Статус оплаты: защита от 0/0 = paid
                                if ($due <= 0) {
                                    $payText = 'UNPAID';
                                    $payCls  = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200';
                                } elseif ($paid <= 0) {
                                    $payText = 'UNPAID';
                                    $payCls  = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200';
                                } elseif ($paid + 0.01 < $due) {
                                    $payText = 'PARTIAL';
                                    $payCls  = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200';
                                } else {
                                    $payText = 'PAID';
                                    $payCls  = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200';
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
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $booking->room->roomType->name }}
                                        </div>
                                    @endif
                                </td>

                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ $booking->date_from->format('d.m.Y') }} — {{ $booking->date_to->format('d.m.Y') }}
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

                                {{-- Оплата --}}
                                <td class="text-gray-800 dark:text-gray-200">
                                    <span class="inline-block px-2 py-1 rounded text-sm {{ $payCls }}">
                                        {{ $payText }}
                                    </span>

                                    @if($invoice)
                                        <div class="mt-2">
                                            <a href="{{ route('invoices.show', $invoice) }}"
                                            class="inline-block px-2 py-1 rounded text-sm border border-gray-200 dark:border-gray-700
                                                    text-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-900/40">
                                                Счёт: {{ $invoice->number }}
                                            </a>
                                        </div>

                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Статус: <b>{{ $invoice->status }}</b>
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                            Нет счёта (оплаты добавляются через счёт)
                                        </div>
                                    @endif

                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ number_format($paid, 2, '.', ' ') }} / {{ number_format($due, 2, '.', ' ') }}
                                    </div>
                                </td>

                                {{-- Сумма --}}
                                <td class="text-gray-800 dark:text-gray-200">
                                    {{ number_format($due, 2, '.', ' ') }}
                                </td>

                                {{-- Действия --}}
                                <td class="text-right whitespace-nowrap">
                                    <div class="flex flex-col sm:flex-row sm:justify-end gap-2">
                                        <a class="px-3 py-1 rounded border border-gray-200 dark:border-gray-700
                                                text-blue-700 dark:text-blue-300 hover:bg-gray-50 dark:hover:bg-gray-900/30"
                                        href="{{ route('bookings.edit', $booking) }}">
                                            Редактировать
                                        </a>

                                        @if($booking->status === 'confirmed')
                                            <form method="POST" action="{{ route('bookings.checkin', $booking) }}" class="inline">
                                                @csrf
                                                <button class="px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700"
                                                        onclick="return confirm('Заселить гостя (Check-in)?')">
                                                    Check-in
                                                </button>
                                            </form>
                                        @endif

                                        @if($booking->status === 'checked_in')
                                            <form method="POST" action="{{ route('bookings.checkout', $booking) }}" class="inline">
                                                @csrf
                                                <button class="px-3 py-1 rounded bg-purple-600 text-white hover:bg-purple-700"
                                                        onclick="return confirm('Оформить выезд (Check-out)?')">
                                                    Check-out
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Удаление: показываем, когда нет счета (или статус pending/cancelled) --}}
                                        @if(!$invoice && in_array($booking->status, ['pending', 'cancelled'], true))
                                            <form method="POST"
                                                action="{{ route('bookings.destroy', $booking) }}"
                                                class="inline"
                                                onsubmit="return confirm('Удалить бронирование #{{ $booking->id }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                                    Удалить
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-4 text-center text-gray-500 dark:text-gray-400">
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
