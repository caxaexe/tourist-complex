<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Наши Номера и Удобства') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Категории номеров</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($roomTypes as $type)
                        <div class="border rounded-lg p-4 bg-gray-50 shadow-sm">
                            <h4 class="font-semibold text-xl text-indigo-600">{{ $type->name }}</h4>
                            <p class="text-gray-600 mt-2 text-sm">{{ $type->description }}</p>
                            <div class="mt-4 flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">{{ number_format($type->base_price, 2) }} ₽ / сутки</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Доступные Номера</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($rooms as $room)
                        <div class="border rounded-lg p-5 flex flex-col justify-between hover:shadow-md transition shadow-sm bg-white">
                            <div>
                                <div class="flex justify-between items-start">
                                    <h4 class="text-xl font-bold text-gray-800">Комната №{{ $room->room_number }}</h4>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $room->roomType->name }}
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-500 mt-1">Этаж: {{ $room->floor }}</p>

                                <div class="mt-4">
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Удобства в номере:</span>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        @forelse($room->amenities as $amenity)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                {{ $amenity->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400 italic">Стандартный набор удобств</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t flex justify-between items-center">
                                <span class="text-sm text-gray-500">Макс. гостей: {{ $room->roomType->max_capacity ?? 'Не указано' }}</span>
                                <a href="{{ route('my.bookings.create', ['room_id' => $room->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Забронировать
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>