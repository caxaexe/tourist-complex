<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Админ-панель
            </h2>

            <div class="text-sm text-gray-500 dark:text-gray-400">
                Вы вошли как: <span class="font-medium">{{ auth()->user()->name }}</span>
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

                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center justify-center px-4 py-2 border rounded text-gray-700 dark:text-gray-200">
                        В общий Dashboard
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <a href="{{ route('clients.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Справочник</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Клиенты</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Добавление, редактирование, удаление гостей
                    </div>
                </a>

                <a href="{{ route('room-types.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Категории</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Типы номеров</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Управление категориями (люкс, стандарт и т.д.)
                    </div>
                </a>

                <a href="{{ route('rooms.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Фонд размещения</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Номера</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Номера, цены за ночь, вместимость, активность
                    </div>
                </a>

                <a href="{{ route('amenities.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Справочник</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Удобства</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Wi-Fi, минибар, кондиционер и др.
                    </div>
                </a>

                <a href="{{ route('bookings.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded p-5 hover:shadow-md transition">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Операции</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Бронирования</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Создание брони, проверка пересечений дат, сумма
                    </div>
                </a>

                {{-- на будущее: пользователи/роли --}}
                <div class="bg-gray-50 dark:bg-gray-900/40 border border-dashed border-gray-300 dark:border-gray-700 rounded p-5">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Скоро</div>
                    <div class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-200">Пользователи и роли</div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Управление доступом (admin/employee)
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
