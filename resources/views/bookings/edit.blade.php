@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Редактирование бронирования #{{ $booking->id }}
            </h2>

            <a href="{{ route($prefix.'bookings.index') }}"
               class="px-3 py-2 rounded border border-gray-200 dark:border-gray-700
                      text-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-900/30">
                ← Назад
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 border border-red-300 rounded text-red-900">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded text-green-900">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                <div class="mb-4 text-sm text-gray-600 dark:text-gray-300">
                    Оплачено: <b>{{ number_format($paidTotal, 2, '.', ' ') }}</b> /
                    К оплате: <b>{{ number_format($dueTotal, 2, '.', ' ') }}</b> —
                    Баланс: <b>{{ number_format($balance, 2, '.', ' ') }}</b>
                </div>

                <form method="POST" action="{{ route($prefix.'bookings.update', $booking) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Клиент</label>
                            <select name="client_id" class="border rounded w-full p-2">
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" @selected(old('client_id', $booking->client_id) == $client->id)>
                                        {{ $client->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Номер</label>
                            <select name="room_id" class="border rounded w-full p-2">
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" @selected(old('room_id', $booking->room_id) == $room->id)>
                                        №{{ $room->number }} — {{ $room->roomType->name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Дата заезда</label>
                            <input type="date" name="date_from"
                                   value="{{ old('date_from', optional($booking->date_from)->format('Y-m-d')) }}"
                                   class="border rounded w-full p-2">
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Дата выезда</label>
                            <input type="date" name="date_to"
                                   value="{{ old('date_to', optional($booking->date_to)->format('Y-m-d')) }}"
                                   class="border rounded w-full p-2">
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Статус</label>
                            <select name="status" class="border rounded w-full p-2">
                                @foreach(['pending','confirmed','cancelled','checked_in','checked_out'] as $st)
                                    <option value="{{ $st }}" @selected(old('status', $booking->status) === $st)>
                                        {{ $st }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Комментарий</label>
                            <input type="text" name="note"
                                   value="{{ old('note', $booking->note) }}"
                                   class="border rounded w-full p-2">
                        </div>

                    </div>

                    {{-- Услуги --}}
                    <div class="mt-6">
                        <div class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">
                            Услуги
                        </div>

                        <div class="space-y-2">
                            @foreach($services as $s)
                                @php $row = $selectedServices[$s->id] ?? ['quantity' => 0, 'price' => (float)$s->price]; @endphp

                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="services[{{ $loop->index }}][id]" value="{{ $s->id }}">

                                    <div class="flex-1 text-gray-800 dark:text-gray-200">
                                        {{ $s->name }}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            ({{ number_format($s->price, 2, '.', ' ') }})
                                        </span>
                                    </div>

                                    <input type="number" min="0"
                                           name="services[{{ $loop->index }}][quantity]"
                                           value="{{ old("services.$loop->index.quantity", $row['quantity']) }}"
                                           class="border rounded w-24 p-2"
                                           placeholder="0">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 flex gap-2">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Сохранить
                        </button>

                        <a href="{{ route($prefix.'bookings.index') }}"
                           class="px-4 py-2 rounded border border-gray-200 dark:border-gray-700
                                  text-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-900/30">
                            Отмена
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>