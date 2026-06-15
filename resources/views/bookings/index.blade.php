@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
    $editRoute = $u?->hasRole('admin') ? 'admin.bookings.edit' : 'staff.bookings.edit';
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Бронирования') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-auth-session-status class="mb-4" :status="session('success')" />
            
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-800 text-red-900 dark:text-red-200">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4 flex flex-col sm:flex-row gap-2">
                <a href="{{ route($prefix.'bookings.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    {{ __('+ Создать бронирование') }}
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded p-4 overflow-auto text-gray-800 dark:text-gray-200">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-700">
                            <th class="py-2">ID</th>
                            <th>{{ __('Клиент') }}</th>
                            <th>{{ __('Комната') }}</th>
                            <th>{{ __('Даты') }}</th>
                            <th>{{ __('Статус') }}</th>
                            <th>{{ __('Сумма') }}</th>
                            <th class="text-right">{{ __('Действия') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            @php
                                $map = ['pending'=>'bg-yellow-900/30 text-yellow-200', 'confirmed'=>'bg-green-900/30 text-green-200', 'cancelled'=>'bg-gray-900/40 text-gray-200', 'checked_in'=>'bg-blue-900/30 text-blue-200', 'checked_out'=>'bg-purple-900/30 text-purple-200'];
                                $cls = $map[$booking->status] ?? 'bg-gray-700';
                            @endphp
                            <tr class="border-b border-gray-700">
                                <td class="py-3">{{ $booking->id }}</td>
                                <td>{{ $booking->client->full_name }}</td>
                                <td>
                                    <div class="font-bold">{{ $booking->room->title ?? 'Номер ' . $booking->room->number }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->room->roomType->name ?? '' }}</div>
                                </td>
                                <td>{{ $booking->date_from->format('d.m.Y') }} — {{ $booking->date_to->format('d.m.Y') }}</td>
                                <td><span class="px-2 py-1 rounded text-xs {{ $cls }}">{{ $booking->status }}</span></td>
                                <td>{{ number_format($booking->total, 2) }}</td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        @if($booking->status === 'pending')
                                            <form method="POST" action="{{ route($prefix.'bookings.confirm', $booking) }}" onsubmit="return confirm('Подтвердить бронь?')">
                                                @csrf <button class="text-green-500 hover:text-green-400 font-bold">Подтвердить</button>
                                            </form>
                                            
                                            <form method="POST" action="{{ route($prefix.'bookings.cancel', $booking) }}" id="cancel-form-{{$booking->id}}">
                                                @csrf
                                                <input type="hidden" name="reason" id="cancel-reason-{{$booking->id}}">
                                                <button type="button" onclick="cancelBooking({{ $booking->id }})" class="text-gray-400 hover:text-gray-200">Отменить</button>
                                            </form>
                                        @endif
                                        @if($booking->status === 'confirmed')
                                            <form method="POST" action="{{ route($prefix.'bookings.checkin', $booking) }}">
                                                @csrf <button class="text-blue-500 hover:text-blue-400">Check-in</button>
                                            </form>
                                        @endif
                                        @if($booking->status === 'checked_in')
                                            <form method="POST" action="{{ route($prefix.'bookings.checkout', $booking) }}">
                                                @csrf <button class="text-purple-500 hover:text-purple-400">Check-out</button>
                                            </form>
                                        @endif
                                        <a href="{{ route($editRoute, $booking) }}" class="text-blue-400">Редактировать</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-4 text-center">Нет данных</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $bookings->links() }}</div>
            </div>
        </div>
    </div>

    <script>
        function cancelBooking(id) {
            let reason = prompt('Введите причину отмены (она будет отправлена клиенту):');
            if (reason !== null && reason.trim() !== '') {
                document.getElementById('cancel-reason-' + id).value = reason;
                document.getElementById('cancel-form-' + id).submit();
            } else if (reason !== null) {
                alert('Причина обязательна для отмены!');
            }
        }
    </script>
</x-app-layout>