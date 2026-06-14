<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Номера') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-950 min-h-screen text-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-800">
                <h3 class="text-lg font-bold text-white mb-4">{{ __('Категории номеров') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($roomTypes as $type)
                        @php
                            $minPrice = $type->rooms->min('price_per_night');
                            $maxPrice = $type->rooms->max('price_per_night');
                        @endphp
                        
                        <div class="border border-gray-800 rounded-lg p-4 bg-gray-950/50 shadow-sm flex flex-col justify-between">
                            <div>
                                <h4 class="font-semibold text-xl text-blue-400">{{ $type->name }}</h4>
                                <p class="text-gray-400 mt-2 text-sm">{{ $type->description }}</p>
                            </div>
                            <div class="mt-4 pt-2 border-t border-gray-800 flex justify-between items-center">
                                <span class="text-lg font-bold text-green-400">
                                    @if($minPrice && $maxPrice)
                                        @if($minPrice == $maxPrice)
                                            {{ number_format($minPrice, 0, '.', ' ') }} {{ __('лей') }}
                                        @else
                                            {{ number_format($minPrice, 0, '.', ' ') }} - {{ number_format($maxPrice, 0, '.', ' ') }} {{ __('лей') }}
                                        @endif
                                    @else
                                        {{ number_format($type->base_price, 0, '.', ' ') }} {{ __('лей') }}
                                    @endif
                                    <span class="text-xs text-gray-500">/ {{ __('сутки') }}</span>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-800">
                <h3 class="text-lg font-bold text-white mb-4">{{ __('Доступные номера') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($rooms as $room)
                        <div class="border border-gray-800 rounded-lg p-5 flex flex-col sm:flex-row gap-5 hover:border-gray-700 transition shadow-sm bg-gray-950/40">
                            
                            <div class="w-full sm:w-32 h-32 flex-shrink-0 bg-gray-900 rounded-lg overflow-hidden border border-gray-800">
                                <img src="{{ asset('images/hotel.jpg') }}" 
                                     alt="{{ $room->title }}" 
                                     class="w-full h-full object-cover opacity-80 hover:opacity-100 transition duration-300" />
                            </div>

                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start gap-2">
                                        <h4 class="text-xl font-bold text-white">
                                            {{ $room->title ?? __('Покои Замка') }}
                                        </h4>
                                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-950 text-blue-300 border border-blue-800/60 flex-shrink-0">
                                            {{ $room->roomType->name ?? __('Классика') }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-400 mt-1 font-medium">
                                        {{ __('Комната №') }}{{ $room->number }}
                                    </p>

                                    @if($room->description)
                                        <p class="text-xs text-gray-400 mt-2 line-clamp-2">{{ $room->description }}</p>
                                    @endif

                                    <div class="mt-4">
                                        <div class="flex flex-wrap gap-1.5 mt-1">
                                            @forelse($room->amenities as $amenity)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-900 text-gray-300 border border-gray-800">
                                                    {{ $amenity->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-500 italic">{{ __('Стандартные удобства замка') }}</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 pt-3 border-t border-gray-900/60 flex justify-between items-center gap-4">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">{{ __('Вместимость:') }} {{ $room->capacity ?? $room->roomType->max_capacity ?? 2 }} {{ __('человека') }}</span>
                                        <span class="text-base font-bold text-green-400">{{ number_format($room->price_per_night, 0, '.', ' ') }} {{ __('лей') }} <span class="text-xs text-gray-500">/ {{ __('ночь') }}</span></span>
                                    </div>
                                    
                                    <a href="{{ route('my.bookings.create', ['room_id' => $room->id]) }}" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                        {{ __('Забронировать') }}
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>