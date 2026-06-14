@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';

    $editRoute = auth()->user()?->hasRole('admin')
      ? 'admin.bookings.edit'
      : 'staff.bookings.edit';
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Бронирования') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-800 text-green-900 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-800 text-red-900 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-800 text-red-900 dark:text-red-200">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4 flex flex-col sm:flex-row gap-2 sm:items-center">
                <a href="{{ route($prefix.'bookings.create') }}"
                   class="sm:ml-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    {{ __('+ Создать бронирование') }}
                </a>
            </div>

            {{-- Фильтр по оплате --}}
            <div class="mb-4 flex flex-wrap items-center gap-2">
                <a href="{{ route($prefix.'bookings.index') }}"
                   class="px-3 py-2 rounded border border-gray-200 dark:border-gray-700 {{ empty($payment) ? 'bg-gray-100 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }} text-gray-800 dark:text-gray-200">
                    {{ __('Все') }}
                </a>
                <a href="{{ route($prefix.'bookings.index', ['payment' => 'unpaid']) }}"
                   class="px-3 py-2 rounded border border-gray-200 dark:border-gray-700 {{ ($payment ?? '') === 'unpaid' ? 'bg-gray-100 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }} text-gray-800 dark:text-gray-200">
                    {{ __('НЕОПЛАЧЕНО') }}
                </a>
                <a href="{{ route($prefix.'bookings.index', ['payment' => 'partial']) }}"
                   class="px-3 py-2 rounded border border-gray-200 dark:border-gray-700 {{ ($payment ?? '') === 'partial' ? 'bg-gray-100 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }} text-gray-800 dark:text-gray-200">
                    {{ __('ЧАСТИЧНО') }}
                </a>
                <a href="{{ route($prefix.'bookings.index', ['payment' => 'paid']) }}"
                   class="px-3 py-2 rounded border border-gray-200 dark:border-gray-700 {{ ($payment ?? '') === 'paid' ? 'bg-gray-100 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }} text-gray-800 dark:text-gray-200">
                    {{ __('ОПЛАЧЕНО') }}
                </a>
            </div>

            {{-- Таблица --}}
            <div class="mt-4 bg-white dark:bg-gray-800 shadow rounded p-4 overflow-auto text-gray-800 dark:text-gray-200">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2">ID</th>
                            <th>{{ __('Клиент') }}</th>
                            <th>{{ __('Комната') }}</th>
                            <th>{{ __('Даты') }}</th>
                            <th>{{ __('Ночей') }}</th>
                            <th>{{ __('Статус') }}</th>
                            <th>{{ __('Оплата') }}</th>
                            <th>{{ __('Сумма') }}</th>
                            <th class="text-right">{{ __('Действия') }}</th>
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
                                $due = (float) ($invoice->total ?? $booking->total ?? 0);
                                $paid = (float) ($booking->payments_sum_amount ?? 0);

                                if ($due <= 0 || $paid <= 0) {
                                    $payText = __('НЕОПЛАЧЕНО'); $payCls = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200';
                                } elseif ($paid + 0.01 < $due) {
                                    $payText = __('ЧАСТИЧНО'); $payCls = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200';
                                } else {
                                    $payText = __('ОПЛАЧЕНО'); $payCls = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200';
                                }
                            @endphp

                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2">{{ $booking->id }}</td>
                                <td>{{ $booking->client->full_name }}</td>
                                
                                {{-- ВЫВОД НАЗВАНИЯ КОМНАТЫ --}}
                                <td>
                                    <div class="font-semibold">{{ $booking->room->title ?? __('Номер ') . $booking->room->number }}</div>
                                    @if($booking->room->roomType)
                                        <div class="text-xs text-gray-500">{{ $booking->room->roomType->name }}</div>
                                    @endif
                                </td>

                                <td>{{ $booking->date_from->format('d.m.Y') }} — {{ $booking->date_to->format('d.m.Y') }}</td>
                                <td>{{ $nights }}</td>
                                <td><span class="px-2 py-1 rounded text-sm {{ $cls }}">{{ $booking->status }}</span></td>
                                <td>
                                    <span class="px-2 py-1 rounded text-sm {{ $payCls }}">{{ $payText }}</span>
                                </td>
                                <td>{{ number_format($due, 2, '.', ' ') }}</td>
                                <td class="text-right">
                                    <a href="{{ route($editRoute, $booking) }}" class="text-blue-500 hover:underline">{{ __('Редактировать') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="py-4 text-center text-gray-500">{{ __('Нет бронирований') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $bookings->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>