@php
    $u = auth()->user();
    $prefix = $u?->hasRole('admin') ? 'admin.' : 'staff.';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Панель управления
            </h2>

            <div class="text-sm text-gray-500 dark:text-gray-400">
                Вы вошли как: <span class="font-medium">{{ auth()->user()->name }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 bg-white dark:bg-gray-800 shadow rounded p-5">
                <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">Быстрая навигация</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Управление сущностями туристического комплекса
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                {{-- Операции --}}
                <a href="{{ route($prefix.'bookings.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Операции</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Бронирования</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Список, редактирование, статусы, check-in/check-out
                    </div>
                </a>

                <a href="{{ route($prefix.'invoices.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Финансы</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Счета (Invoices)</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Создание, просмотр, статусы, позиции счёта
                    </div>
                </a>

                <a href="{{ route($prefix.'payments.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Финансы</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Оплаты (Payments)</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        История оплат, добавление/удаление
                    </div>
                </a>

                {{-- Справочники --}}
                <a href="{{ route($prefix.'clients.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Справочник</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Клиенты</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Гости: добавление, редактирование, удаление
                    </div>
                </a>

                <a href="{{ route($prefix.'rooms.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Фонд размещения</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Номера</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Номера, цены/ночь, активность, типы
                    </div>
                </a>

                <a href="{{ route($prefix.'room-types.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Категории</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Типы номеров</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Стандарт/люкс и т.д.
                    </div>
                </a>

                <a href="{{ route($prefix.'amenities.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Справочник</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Удобства</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Wi-Fi, минибар, кондиционер и др.
                    </div>
                </a>

                <a href="{{ route($prefix.'services.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Справочник</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Услуги</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Доп. услуги, цены, расчёт
                    </div>
                </a>

                {{-- Только админ --}}
                @if($u?->hasRole('admin'))
                    <a href="{{ route('admin.users.index') }}"
                       class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Администрирование</div>
                        <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Пользователи</div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Управление пользователями/ролями
                        </div>
                    </a>

                    <a href="{{ route('admin.audit-logs.index') }}"
                       class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Контроль</div>
                        <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Audit Logs</div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Журнал действий
                        </div>
                    </a>

                    <a href="{{ route('admin.reports.index') }}"
                       class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Аналитика</div>
                        <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Отчёты</div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Сводки и отчёты
                        </div>
                    </a>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>