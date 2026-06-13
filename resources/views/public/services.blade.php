<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Наши Услуги') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-600 mb-6 text-sm md:text-base">
                    Чтобы сделать ваше пребывание в нашем туристическом комплексе максимально комфортным, мы рады предложить вам широкий спектр дополнительных услуг. Вы можете заказать их на стойке регистрации или при бронировании.
                </p>

                <div class="divide-y divide-gray-200">
                    @forelse($services as $service)
                        <div class="py-4 flex justify-between items-start space-x-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900">{{ $service->name }}</h3>
                                @if($service->description)
                                    <p class="text-gray-600 text-sm mt-1">{{ $service->description }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800">
                                    {{ number_format($service->price, 2) }} ₽
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