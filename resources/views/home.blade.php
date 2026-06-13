<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Castle Noctem') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-950 text-gray-100">
    <header class="sticky top-0 z-50 border-b border-gray-800/80 bg-gray-950/80 backdrop-blur">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-1">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <span class="text-white font-semibold tracking-wide">
                        {{ __('Castle Noctem') }}
                    </span>
                </a>
                {{-- СЕКРЕТНАЯ ТОЧКА ВХОДА --}}
                @if (!auth()->check() && Route::has('login'))
                    <a href="{{ route('login') }}" class="text-gray-600/30 hover:text-blue-500/50 transition-colors cursor-default text-sm select-none" title="">.</a>
                @endif
            </div>

            {{-- Десктопное меню --}}
            <nav class="hidden sm:flex items-center gap-2">
                <a href="#about"
                   class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition">
                    {{ __('Об отеле') }}
                </a>
                
                <a href="{{ route('public.rooms') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition">
                    {{ __('Номера и удобства') }}
                </a>
                <a href="{{ route('public.services') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition">
                    {{ __('Услуги') }}
                </a>

                <a href="#contacts"
                   class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition">
                    {{ __('Контакты') }}
                </a>

                <a href="{{ route('my.bookings.index') }}"
                   class="ml-2 inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition">
                    {{ __('Мои заявки') }}
                </a>

                <div class="relative ml-2" x-data="{ langMenu: false }" @keydown.escape.window="langMenu=false">
                    <button @click="langMenu=!langMenu" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition uppercase">
                        {{ app()->getLocale() }} ▾
                    </button>
                    <div x-show="langMenu" x-transition @click.outside="langMenu=false" class="absolute right-0 mt-2 w-32 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden z-50">
                        <a href="{{ route('lang.switch', 'ru') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">Русский</a>
                        <a href="{{ route('lang.switch', 'ro') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">Română</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">English</a>
                    </div>
                </div>

                @if (auth()->check())
                    <a href="{{ auth()->user()->hasRole('admin') || (method_exists(auth()->user(), 'isStaff') ? auth()->user()->isStaff() : auth()->user()->hasAnyRole(['staff', 'employee'])) ? route('dashboard') : route('my.bookings.index') }}"
                       class="inline-flex items-center px-4 py-2 rounded-md border border-gray-700 text-gray-200 hover:text-white hover:bg-gray-800 transition">
                        {{ __('Личный кабинет') }}
                    </a>
                @endif
            </nav>

            {{-- Мобильное меню --}}
            <div class="flex sm:hidden items-center gap-2">
                <a href="{{ route('public.rooms') }}"
                   class="px-2 py-1 rounded border border-gray-800 text-gray-300 text-xs hover:text-white">
                    {{ __('Номера') }}
                </a>
                <a href="{{ route('public.services') }}"
                   class="px-2 py-1 rounded border border-gray-800 text-gray-300 text-xs hover:text-white">
                    {{ __('Услуги') }}
                </a>
                <a href="{{ route('my.bookings.index') }}"
                   class="inline-flex items-center px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition text-sm">
                    {{ __('Заявки') }}
                </a>
                <div class="relative ml-2" x-data="{ langMenu: false }" @keydown.escape.window="langMenu=false">
                    <button @click="langMenu=!langMenu" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition uppercase">
                        {{ app()->getLocale() }} ▾
                    </button>
                    <div x-show="langMenu" x-transition @click.outside="langMenu=false" class="absolute right-0 mt-2 w-32 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden z-50">
                        <a href="{{ route('lang.switch', 'ru') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">Русский</a>
                        <a href="{{ route('lang.switch', 'ro') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">Română</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">English</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="relative">
        <div class="absolute inset-0">
            <img src="{{ asset('images/hotel.jpg') }}"
                 alt="Hotel"
                 class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-b from-gray-950/80 via-gray-950/60 to-gray-950"></div>
        </div>

        <div class="relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-gray-700/70 bg-gray-950/40 text-sm text-gray-200">
                        <span class="w-2 h-2 rounded-full bg-green-400"></span>
                        {{ __('Открыты для бронирования') }}
                    </div>

                    <h1 class="mt-5 text-4xl sm:text-5xl font-semibold tracking-tight text-white">
                        {{ __('Castle Noctem') }}
                    </h1>

                    <p class="mt-4 text-lg text-gray-200">
                        {{ __('Мистический замок в сердце гор Трасильвании. Старинная архитектура, туман над вершинами, тишина хвойных лесов и комфорт современного курорта. Здесь легенды оживают, а отдых становится по-настоящему незабываемым.') }}
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('my.bookings.index') }}"
                           class="inline-flex justify-center items-center px-6 py-3 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition">
                            {{ __('Мои заявки') }}
                        </a>
                        <a href="#about"
                           class="inline-flex justify-center items-center px-6 py-3 rounded-md border border-gray-700 text-gray-200 hover:text-white hover:bg-gray-800 transition">
                            {{ __('Подробнее об отеле') }}
                        </a>
                    </div>

                    <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="rounded-lg border border-gray-800 bg-gray-900/40 p-4">
                            <div class="text-sm text-gray-400">{{ __('Заезд') }}</div>
                            <div class="mt-1 text-base font-medium text-gray-100">{{ __('С 14:00') }}</div>
                        </div>
                        <div class="rounded-lg border border-gray-800 bg-gray-900/40 p-4">
                            <div class="text-sm text-gray-400">{{ __('Выезд') }}</div>
                            <div class="mt-1 text-base font-medium text-gray-100">{{ __('До 12:00') }}</div>
                        </div>
                        <div class="rounded-lg border border-gray-800 bg-gray-900/40 p-4">
                            <div class="text-sm text-gray-400">{{ __('Поддержка') }}</div>
                            <div class="mt-1 text-base font-medium text-gray-100">24/7</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2) ABOUT --}}
    <section id="about" class="py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <h2 class="text-3xl sm:text-4xl font-semibold text-white">
                {{ __('Об отеле') }}
            </h2>

            <p class="mt-6 text-gray-300 leading-relaxed max-w-3xl mx-auto">
                {{ __('Castle Noctem - это исторический замок, расположенный высоко в горах Трансильвании, в окружении хвойных лесов и снежных склонов. Архитектура вдохновлена средневековыми крепостями региона - массивные каменные стены, башни и витражные окна. Несмотря на мистическую атмосферу, внутри вас ждёт комфорт премиального горного курорта.') }}
            </p>

            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="rounded-xl border border-gray-800 bg-gray-900 p-6 text-left">
                <div class="text-sm text-gray-400 uppercase tracking-wider">{{ __('КОМФОРТ') }}</div>
                <div class="mt-2 text-gray-200">
                    {{ __('Каминные залы, панорамные виды на горы, средневековый интерьер с современным комфортом.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-800 bg-gray-900 p-6 text-left">
                <div class="text-sm text-gray-400 uppercase tracking-wider">{{ __('УДОБСТВА') }}</div>
                <div class="mt-2 text-gray-200">
                    {{ __('SPA-зона и сауна, тёплые номера с видом на горы, ресторан с трансильванской кухней.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-800 bg-gray-900 p-6 text-left">
                <div class="text-sm text-gray-400 uppercase tracking-wider">{{ __('РАСПОЛОЖЕНИЕ') }}</div>
                <div class="mt-2 text-gray-200">
                    {{ __('Замок расположен на высоте 1400 метров, окружён хвойными лесами и горнолыжными трассами.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-800 bg-gray-900 p-6 text-left">
                <div class="text-sm text-gray-400 uppercase tracking-wider">{{ __('СЕРВИС') }}</div>
                <div class="mt-2 text-gray-200">
                    {{ __('Персональный подход и поддержка 24/7.') }}
                </div>
            </div>
        </div>
        </div>
    </section>

    {{-- 3) BOOKING BLOCK --}}
    <section class="py-20 border-t border-gray-900">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-gray-800 bg-gray-900 p-8 text-center">
                <h3 class="text-2xl font-semibold text-white">
                    {{ __('Как подать заявку') }}
                </h3>

                <ol class="mt-6 space-y-4 text-gray-300 text-left max-w-md mx-auto">
                    <li class="flex gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-gray-800 text-gray-200 text-sm">1</span>
                        {{ __('Выберите номер и даты.') }}
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-gray-800 text-gray-200 text-sm">2</span>
                        {{ __('Укажите телефон и email.') }}
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-gray-800 text-gray-200 text-sm">3</span>
                        {{ __('Отправьте заявку - мы подтвердим её.') }}
                    </li>
                </ol>

                <div class="mt-8">
                    <a href="{{ route('my.bookings.index') }}"
                       class="inline-flex justify-center items-center px-6 py-3 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition">
                        {{ __('Мои заявки') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="contacts" class="py-16 sm:py-20 border-t border-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <h2 class="text-2xl sm:text-3xl font-semibold text-white">
                        {{ __('Контакты') }}
                    </h2>
                    <p class="mt-3 text-gray-300">
                        {{ __('Свяжитесь с нами и забронируйте пребывание в замке, где легенды становятся частью реальности.') }}
                    </p>
                </div>

                <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="rounded-lg border border-gray-800 bg-gray-900 p-5">
                        <div class="text-sm text-gray-400">{{ __('Телефон') }}</div>
                        <div class="mt-1 text-gray-200 font-medium">+373 XX XXX XXX</div>
                        <div class="mt-2 text-sm text-gray-400">{{ __('Ежедневно 09:00–21:00') }}</div>
                    </div>

                    <div class="rounded-lg border border-gray-800 bg-gray-900 p-5">
                        <div class="text-sm text-gray-400">{{ __('Email') }}</div>
                        <div class="mt-1 text-gray-200 font-medium">caxa5578@gmail.com</div>
                        <div class="mt-2 text-sm text-gray-400">{{ __('Ответ в течение дня') }}</div>
                    </div>

                    <div class="rounded-lg border border-gray-800 bg-gray-900 p-5">
                        <div class="text-sm text-gray-400">{{ __('Адрес') }}</div>
                        <div class="mt-1 text-gray-200 font-medium">{{ __('Carpathian Mountains') }}</div>
                        <div class="mt-2 text-sm text-gray-400">{{ __('Romania, Transylvania') }}</div>
                    </div>
                </div>
            </div>

            {{-- optional note --}}
            <div class="mt-8 rounded-lg border border-gray-800 bg-gray-900 p-5 text-gray-300">
                <div class="text-sm text-gray-400">{{ __('Важно') }}</div>
                <div class="mt-1">
                    {{ __('Для подтверждения заявки мы используем указанные телефон и email.') }}
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="border-t border-gray-900 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="text-sm text-gray-400">
                &copy; {{ date('Y') }} {{ __('Castle Noctem. Между горами и мифами.') }}
            </div>

            <div class="flex items-center gap-3">
                <a href="#about" class="text-sm text-gray-300 hover:text-white hover:underline">{{ __('Об отеле') }}</a>
                <a href="#contacts" class="text-sm text-gray-300 hover:text-white hover:underline">{{ __('Контакты') }}</a>
            </div>
        </div>
    </footer>
</body>
</html>