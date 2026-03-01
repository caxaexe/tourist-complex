<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Castle Noctem</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-950 text-gray-100">
    <header class="sticky top-0 z-50 border-b border-gray-800/80 bg-gray-950/80 backdrop-blur">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <span class="text-white font-semibold tracking-wide">
                    Castle Noctem
                </span>
            </a>

            <nav class="hidden sm:flex items-center gap-2">
                <a href="#about"
                   class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition">
                    Об отеле
                </a>
                <a href="#contacts"
                   class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition">
                    Контакты
                </a>

                <a href="{{ route('my.bookings.create') }}"
                   class="ml-2 inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition">
                    Подать заявку
                </a>

                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-4 py-2 rounded-md border border-gray-700 text-gray-200 hover:text-white hover:bg-gray-800 transition">
                        Вход
                    </a>
                @endif
            </nav>

            <div class="flex sm:hidden items-center gap-2">
                <a href="{{ route('my.bookings.create') }}"
                   class="inline-flex items-center px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition text-sm">
                    Заявка
                </a>
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-3 py-2 rounded-md border border-gray-700 text-gray-200 hover:text-white hover:bg-gray-800 transition text-sm">
                        Вход
                    </a>
                @endif
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
                        Открыты для бронирования
                    </div>

                    <h1 class="mt-5 text-4xl sm:text-5xl font-semibold tracking-tight text-white">
                        Castle Noctem
                    </h1>

                    <p class="mt-4 text-lg text-gray-200">
                        Мистический замок в сердце гор Трансильвании. Старинная архитектура, туман над вершинами, тишина хвойных лесов и
                        комфорт современного курорта. Здесь легенды оживают, а отдых становится по-настоящему незабываемым.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('my.bookings.create') }}"
                           class="inline-flex justify-center items-center px-6 py-3 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition">
                            Подать заявку на бронирование
                        </a>
                        <a href="#about"
                           class="inline-flex justify-center items-center px-6 py-3 rounded-md border border-gray-700 text-gray-200 hover:text-white hover:bg-gray-800 transition">
                            Подробнее об отеле
                        </a>
                    </div>

                    <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="rounded-lg border border-gray-800 bg-gray-900/40 p-4">
                            <div class="text-sm text-gray-400">Заезд</div>
                            <div class="mt-1 text-base font-medium text-gray-100">С 14:00</div>
                        </div>
                        <div class="rounded-lg border border-gray-800 bg-gray-900/40 p-4">
                            <div class="text-sm text-gray-400">Выезд</div>
                            <div class="mt-1 text-base font-medium text-gray-100">До 12:00</div>
                        </div>
                        <div class="rounded-lg border border-gray-800 bg-gray-900/40 p-4">
                            <div class="text-sm text-gray-400">Поддержка</div>
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
            Об отеле
        </h2>

        <p class="mt-6 text-gray-300 leading-relaxed max-w-3xl mx-auto">
            Castle Noctem - это исторический замок, расположенный высоко в горах Трансильвании,
            в окружении хвойных лесов и снежных склонов.

            Архитектура вдохновлена средневековыми крепостями региона -
            массивные каменные стены, башни и витражные окна.
            Несмотря на мистическую атмосферу, внутри вас ждёт комфорт
            премиального горного курорта.
        </p>

        <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 gap-6">

            <div class="rounded-xl border border-gray-800 bg-gray-900 p-6 text-left">
                <div class="text-sm text-gray-400 uppercase tracking-wider">Комфорт</div>
                <div class="mt-2 text-gray-200">
                    Каминные залы, панорамные виды на горы,
                    средневековый интерьер с современным комфортом.
                </div>
            </div>

            <div class="rounded-xl border border-gray-800 bg-gray-900 p-6 text-left">
                <div class="text-sm text-gray-400 uppercase tracking-wider">Удобства</div>
                <div class="mt-2 text-gray-200">
                    SPA-зона и сауна, тёплые номера с видом на горы,
                    ресторан с трансильванской кухней.
                </div>
            </div>

            <div class="rounded-xl border border-gray-800 bg-gray-900 p-6 text-left">
                <div class="text-sm text-gray-400 uppercase tracking-wider">Расположение</div>
                <div class="mt-2 text-gray-200">
                    Замок расположен на высоте 1400 метров,
                    окружён хвойными лесами и горнолыжными трассами.
                </div>
            </div>

            <div class="rounded-xl border border-gray-800 bg-gray-900 p-6 text-left">
                <div class="text-sm text-gray-400 uppercase tracking-wider">Сервис</div>
                <div class="mt-2 text-gray-200">
                    Персональный подход и поддержка 24/7.
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
                    Как подать заявку
                </h3>

                <ol class="mt-6 space-y-4 text-gray-300 text-left max-w-md mx-auto">
                    <li class="flex gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-gray-800 text-gray-200 text-sm">1</span>
                        Выберите номер и даты.
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-gray-800 text-gray-200 text-sm">2</span>
                        Укажите телефон и email.
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-gray-800 text-gray-200 text-sm">3</span>
                        Отправьте заявку - мы подтвердим её.
                    </li>
                </ol>

                <div class="mt-8">
                    <a href="{{ route('my.bookings.create') }}"
                    class="inline-flex justify-center items-center px-6 py-3 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition">
                        Перейти к заявке
                    </a>
                    <div class="mt-3 text-xs text-gray-400">
                        * Заявка не требует регистрации.
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="contacts" class="py-16 sm:py-20 border-t border-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <h2 class="text-2xl sm:text-3xl font-semibold text-white">
                        Контакты
                    </h2>
                    <p class="mt-3 text-gray-300">
                        Свяжитесь с нами и забронируйте пребывание в замке, где легенды становятся частью реальности.
                    </p>
                </div>

                <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="rounded-lg border border-gray-800 bg-gray-900 p-5">
                        <div class="text-sm text-gray-400">Телефон</div>
                        <div class="mt-1 text-gray-200 font-medium">+373 XX XXX XXX</div>
                        <div class="mt-2 text-sm text-gray-400">Ежедневно 09:00–21:00</div>
                    </div>

                    <div class="rounded-lg border border-gray-800 bg-gray-900 p-5">
                        <div class="text-sm text-gray-400">Email</div>
                        <div class="mt-1 text-gray-200 font-medium">drakula@example.com</div>
                        <div class="mt-2 text-sm text-gray-400">Ответ в течение дня</div>
                    </div>

                    <div class="rounded-lg border border-gray-800 bg-gray-900 p-5">
                        <div class="text-sm text-gray-400">Адрес</div>
                        <div class="mt-1 text-gray-200 font-medium">Carpathian Mountains</div>
                        <div class="mt-2 text-sm text-gray-400">Romania, Transylvania</div>
                    </div>
                </div>
            </div>

            {{-- optional note --}}
            <div class="mt-8 rounded-lg border border-gray-800 bg-gray-900 p-5 text-gray-300">
                <div class="text-sm text-gray-400">Важно</div>
                <div class="mt-1">
                    Для подтверждения заявки мы используем указанные телефон и email.
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="border-t border-gray-900 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="text-sm text-gray-400">
                © {{ date('Y') }} Castle Noctem. Between mountains and myths.
            </div>

            <div class="flex items-center gap-3">
                <a href="#about" class="text-sm text-gray-300 hover:text-white hover:underline">Об отеле</a>
                <a href="#contacts" class="text-sm text-gray-300 hover:text-white hover:underline">Контакты</a>
                <a href="{{ route('my.bookings.index') }}" class="text-sm text-gray-300 hover:text-white hover:underline">Мои заявки</a>
            </div>
        </div>
    </footer>
</body>
</html>