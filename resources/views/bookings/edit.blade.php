@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Редактировать бронирование #{{ $booking->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 border border-red-300 rounded text-red-900">
                    Проверь поля формы — есть ошибки.
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">

                <form method="POST"
                      action="{{ route($prefix.'bookings.update', $booking) }}"
                      class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-1 text-gray-800 dark:text-gray-200">Клиент *</label>
                        <select name="client_id" class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                    @selected(old('client_id', $booking->client_id) == $client->id)>
                                    {{ $client->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-800 dark:text-gray-200">Номер *</label>
                        <select name="room_id" class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}"
                                    @selected(old('room_id', $booking->room_id) == $room->id)>
                                    №{{ $room->number }}
                                </option>
                            @endforeach
                        </select>
                        @error('room_id') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block mb-1 text-gray-800 dark:text-gray-200">Дата заезда *</label>
                            <input type="date"
                                   name="date_from"
                                   value="{{ old('date_from', $booking->date_from->format('Y-m-d')) }}"
                                   class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                            @error('date_from') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="w-1/2">
                            <label class="block mb-1 text-gray-800 dark:text-gray-200">Дата выезда *</label>
                            <input type="date"
                                   name="date_to"
                                   value="{{ old('date_to', $booking->date_to->format('Y-m-d')) }}"
                                   class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                            @error('date_to') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-800 dark:text-gray-200">Статус</label>
                        <select name="status" class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                            @foreach(['pending','confirmed','cancelled','checked_in','checked_out'] as $status)
                                <option value="{{ $status }}"
                                    @selected(old('status', $booking->status) == $status)>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-gray-800 dark:text-gray-200">Примечание</label>
                        <textarea name="note"
                                  class="border rounded w-full px-3 py-2 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700"
                                  rows="3">{{ old('note', $booking->note) }}</textarea>
                        @error('note') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-6">
                        <h3 class="font-semibold mb-2 text-gray-800 dark:text-gray-200">Дополнительные услуги</h3>

                        <div class="space-y-3">
                            @foreach($services as $index => $service)
                                @php
                                    $qtyOld = old("services.$index.quantity");
                                    $selected = isset($selectedServices[$service->id]);
                                    $quantity = $qtyOld ?? ($selected ? $selectedServices[$service->id]['quantity'] : 0);
                                    $quantity = (int)($quantity ?? 0);
                                @endphp

                                <div class="flex items-center gap-4">
                                    <input type="hidden"
                                           name="services[{{ $index }}][id]"
                                           value="{{ $service->id }}">

                                    <input type="checkbox"
                                           class="h-4 w-4"
                                           @checked($quantity > 0)
                                           onchange="document.getElementById('qty_{{ $service->id }}').disabled = !this.checked; if(!this.checked){document.getElementById('qty_{{ $service->id }}').value='';}">

                                    <div class="flex-1 text-gray-800 dark:text-gray-200">
                                        {{ $service->name }}
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            ({{ number_format($service->price,2,'.',' ') }})
                                        </span>
                                    </div>

                                    <input id="qty_{{ $service->id }}"
                                           type="number"
                                           name="services[{{ $index }}][quantity]"
                                           min="1"
                                           value="{{ $quantity > 0 ? $quantity : '' }}"
                                           class="border rounded px-3 py-2 w-24 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700"
                                           {{ $quantity > 0 ? '' : 'disabled' }}>
                                </div>
                            @endforeach
                        </div>

                        @error('services') <div class="text-red-600 mt-2">{{ $message }}</div> @enderror
                        @error('services.*.id') <div class="text-red-600 mt-2">{{ $message }}</div> @enderror
                        @error('services.*.quantity') <div class="text-red-600 mt-2">{{ $message }}</div> @enderror
                    </div>

                    <hr class="my-6">

                    <div class="flex flex-wrap gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Сохранить
                        </button>

                        <a href="{{ route($prefix.'bookings.index') }}"
                           class="px-4 py-2 border rounded text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700">
                            Назад
                        </a>

                        <form method="POST"
                              action="{{ route($prefix.'bookings.destroy', $booking) }}"
                              onsubmit="return confirm('ТОЧНО удалить бронирование #{{ $booking->id }}? Это действие нельзя отменить.')">
                            @csrf
                            @method('DELETE')
                            <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Удалить бронирование
                            </button>
                        </form>

                        @if($booking->status === 'confirmed')
                            <form method="POST" action="{{ route($prefix.'bookings.checkin', $booking) }}">
                                @csrf
                                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                                        onclick="return confirm('Заселить гостя (Check-in)?')">
                                    Check-in
                                </button>
                            </form>
                        @endif

                        @if($booking->status === 'checked_in')
                            <form method="POST" action="{{ route($prefix.'bookings.checkout', $booking) }}">
                                @csrf
                                <button class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700"
                                        onclick="return confirm('Оформить выезд (Check-out)?')">
                                    Check-out
                                </button>
                            </form>
                        @endif
                    </div>
                </form>

                <hr class="my-6">
                <form method="POST" action="{{ route($prefix.'bookings.invoices.store', $booking) }}">
                    @csrf
                    <button class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-black">
                        Создать счёт (Invoice)
                    </button>
                </form>

                @error('invoice')
                    <div class="text-red-600 mt-2">{{ $message }}</div>
                @enderror

            </div>
        </div>
    </div>
</x-app-layout>