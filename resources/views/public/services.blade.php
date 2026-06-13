<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Наши Услуги') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-950 min-h-screen text-gray-100">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-800">
                <p class="text-gray-400 mb-6 text-sm md:text-base">
                    Чтобы сделать ваше пребывание в Castle Noctem максимально запоминающимся, мы рады предложить вам спектр дополнительных услуг. Вы можете активировать их на стойке регистрации замка или указать при бронировании.
                </p>

                <div class="divide-y divide-gray-800">
                    @forelse($services as $service)
                        <div class="py-4 flex items-center justify-between space-x-4">
                            
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 flex-shrink-0 bg-gray-950 rounded-md border border-gray-800 flex items-center justify-center text-xl text-blue-400">
                                    🔮
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $service->name }}</h3>
                                    @if($service->description)
                                        <p class="text-gray-400 text-sm mt-0.5">{{ $service->description }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-950 text-green-400 border border-green-900/50">
                                    §{{ number_format($service->price, 0, '.', ' ') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 italic py-4 text-center">На данный момент список дополнительных услуг пуст.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>