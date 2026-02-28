@php
    $u = auth()->user();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 dark:text-gray-200 leading-tight">
                Кабинет клиента
            </h2>

            <div class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-400">
                Вы вошли как: <span class="font-medium">{{ $u->name }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 bg-white dark:bg-gray-800 shadow rounded p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200 dark:text-gray-200">Мои бронирования</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-400">
                            Создавайте заявки и отслеживайте статус
                        </div>
                    </div>

                    <a href="{{ url('/') }}"
                       class="inline-flex items-center justify-center px-4 py-2 border rounded text-gray-700 dark:text-gray-200 dark:text-gray-200 border-gray-200 dark:border-gray-700 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        На главную
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <a href="{{ route('my.bookings.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-400">Мои данные</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200 dark:text-gray-200">Список бронирований</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300 dark:text-gray-300">
                        История заявок, статусы, детали
                    </div>
                </a>

                <a href="{{ route('my.bookings.create') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-400">Новая заявка</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200 dark:text-gray-200">Создать бронирование</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300 dark:text-gray-300">
                        Выберите даты и отправьте запрос
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400 dark:text-gray-400">Аккаунт</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200 dark:text-gray-200">Профиль</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300 dark:text-gray-300">
                        Изменить email/пароль и данные профиля
                    </div>
                </a>

            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-black hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        Выйти
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
