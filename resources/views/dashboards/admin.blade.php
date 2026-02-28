@php
    $u = auth()->user();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Админ-панель
            </h2>

            <div class="text-sm text-gray-500 dark:text-gray-400">
                Вы вошли как: <span class="font-medium">{{ $u->name }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 bg-white dark:bg-gray-800 shadow rounded p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">Быстрая навигация</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Управление сущностями туристического комплекса
                        </div>
                    </div>

                    <a href="{{ url('/') }}"
                       class="inline-flex items-center justify-center px-4 py-2 border rounded text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        На главную
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <a href="{{ route('admin.bookings.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Операции</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Бронирования</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Создание/редактирование брони, check-in/out, счета
                    </div>
                </a>

                <a href="{{ route('admin.clients.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Справочник</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Клиенты</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Добавление, редактирование, удаление гостей
                    </div>
                </a>

                <a href="{{ route('admin.room-types.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Категории</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Типы номеров</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Управление категориями (люкс, стандарт и т.д.)
                    </div>
                </a>

                <a href="{{ route('admin.rooms.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Фонд размещения</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Номера</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Номера, цены, вместимость, активность
                    </div>
                </a>

                <a href="{{ route('admin.amenities.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Справочник</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Удобства</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Wi‑Fi, минибар, кондиционер и др.
                    </div>
                </a>

                <a href="{{ route('admin.services.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Дополнительно</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Услуги</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Доп.услуги и цены (сауна, завтрак и т.д.)
                    </div>
                </a>

                <a href="{{ route('admin.invoices.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Финансы</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Счета (Invoices)</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Список счетов, статусы, печать/экспорт
                    </div>
                </a>

                <a href="{{ route('admin.payments.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Финансы</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Платежи</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Внесения оплат и история платежей
                    </div>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Доступ</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Пользователи</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Создание/редактирование сотрудников и ролей
                    </div>
                </a>

                <a href="{{ route('admin.audit-logs.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Контроль</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Audit Logs</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        История действий (создание/обновление/удаление)
                    </div>
                </a>

                <a href="{{ route('admin.reports.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Аналитика</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Отчёты</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Сводки по бронированиям и финансам
                    </div>
                </a>

            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('profile.edit') }}"
                   class="px-4 py-2 border rounded text-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/40">
                    Профиль
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-black">
                        Выйти
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
