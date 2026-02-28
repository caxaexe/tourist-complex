@php
    $isAuth = auth()->check();
    $u = auth()->user();
    $u?->loadMissing('roles');

    $isAdmin = $u?->hasRole('admin') ?? false;
    $isEmployee = $u?->hasRole('employee') ?? false;
    $isStaffOrAdmin = $isAuth && ($isAdmin || $isEmployee);

    $workPrefix = $isAdmin ? 'admin.' : ($isEmployee ? 'staff.' : null);
@endphp

<nav x-data="{ open:false }" class="bg-gray-950 border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Left -->
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <span class="h-8 w-8 rounded bg-gray-800 flex items-center justify-center text-gray-200 font-bold">
                        H
                    </span>
                    <span class="text-gray-200 text-sm font-semibold hidden sm:inline">
                        {{ config('app.name', 'Hotel') }}
                    </span>
                </a>

                <!-- Desktop menu -->
                <div class="hidden sm:flex sm:items-center sm:gap-2">

                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition
                              {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800' }}">
                        Dashboard
                    </a>

                    {{-- WORK AREA (admin/employee) --}}
                    @if($workPrefix)
                        {{-- Номера --}}
                        <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                            <button @click="dd=!dd"
                                    class="px-3 py-2 rounded-md text-sm font-medium transition inline-flex items-center gap-2
                                           text-gray-200 hover:text-white hover:bg-gray-800"
                                    :class="dd ? 'bg-gray-800 text-white' : ''">
                                Номера
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <div x-show="dd" x-transition @click.outside="dd=false"
                                 class="absolute z-50 mt-2 w-60 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                                <a href="{{ route($workPrefix.'room-types.index') }}"
                                   class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    Типы номеров
                                </a>
                                <a href="{{ route($workPrefix.'rooms.index') }}"
                                   class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    Номера
                                </a>
                                <a href="{{ route($workPrefix.'amenities.index') }}"
                                   class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    Удобства
                                </a>
                                <a href="{{ route($workPrefix.'services.index') }}"
                                   class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    Услуги
                                </a>
                            </div>
                        </div>

                        {{-- Бронирование --}}
                        <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                            <button @click="dd=!dd"
                                    class="px-3 py-2 rounded-md text-sm font-medium transition inline-flex items-center gap-2
                                           text-gray-200 hover:text-white hover:bg-gray-800"
                                    :class="dd ? 'bg-gray-800 text-white' : ''">
                                Бронирование
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <div x-show="dd" x-transition @click.outside="dd=false"
                                 class="absolute z-50 mt-2 w-60 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                                <a href="{{ route($workPrefix.'bookings.index') }}"
                                   class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    Бронирования
                                </a>
                                <a href="{{ route($workPrefix.'clients.index') }}"
                                   class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    Клиенты
                                </a>
                            </div>
                        </div>

                        {{-- Финансы --}}
                        <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                            <button @click="dd=!dd"
                                    class="px-3 py-2 rounded-md text-sm font-medium transition inline-flex items-center gap-2
                                           text-gray-200 hover:text-white hover:bg-gray-800"
                                    :class="dd ? 'bg-gray-800 text-white' : ''">
                                Финансы
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <div x-show="dd" x-transition @click.outside="dd=false"
                                 class="absolute z-50 mt-2 w-60 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                                <a href="{{ route($workPrefix.'invoices.index') }}"
                                   class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    Счета
                                </a>
                                <a href="{{ route($workPrefix.'payments.index') }}"
                                   class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    Оплаты
                                </a>
                                @if($isAdmin)
                                    <a href="{{ route('admin.reports.index') }}"
                                       class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                        Отчёты
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Администрирование (admin) --}}
                        @if($isAdmin)
                            <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                                <button @click="dd=!dd"
                                        class="px-3 py-2 rounded-md text-sm font-medium transition inline-flex items-center gap-2
                                               text-gray-200 hover:text-white hover:bg-gray-800"
                                        :class="dd ? 'bg-gray-800 text-white' : ''">
                                    Администрирование
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                                    </svg>
                                </button>

                                <div x-show="dd" x-transition @click.outside="dd=false"
                                     class="absolute z-50 mt-2 w-72 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                                    <a href="{{ route('admin.users.index') }}"
                                       class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                        Персонал
                                    </a>
                                    <a href="{{ route('admin.audit-logs.index') }}"
                                       class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                        Журнал действий
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif

                    {{-- CLIENT AREA (ГОСТИ) --}}
                    @if(!$isStaffOrAdmin)
                        <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                            <button @click="dd=!dd"
                                    class="px-3 py-2 rounded-md text-sm font-medium transition inline-flex items-center gap-2
                                           text-gray-200 hover:text-white hover:bg-gray-800"
                                    :class="dd ? 'bg-gray-800 text-white' : ''">
                                Мои заявки
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <div x-show="dd" x-transition @click.outside="dd=false"
                                 class="absolute z-50 mt-2 w-60 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                                <a href="{{ route('my.bookings.index') }}"
                                   class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    Список заявок
                                </a>
                                <a href="{{ route('my.bookings.create') }}"
                                   class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    Подать заявку
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Right -->
            <div class="hidden sm:flex sm:items-center">
                @if($isAuth)
                    <div class="relative" x-data="{ userOpen:false }" @keydown.escape.window="userOpen=false">
                        <button @click="userOpen=!userOpen"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition">
                            <span>{{ $u->name }}</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                            </svg>
                        </button>

                        <div x-show="userOpen" x-transition @click.outside="userOpen=false"
                             class="absolute right-0 z-50 mt-2 w-48 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                            <a href="{{ route('profile.edit') }}"
                               class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                Профиль
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    Выйти
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm text-gray-200 hover:text-white hover:underline">Login</a>
                        <a href="{{ route('register') }}" class="text-sm text-gray-200 hover:text-white hover:underline">Register</a>
                    </div>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-800">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium
                      {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-200 hover:bg-gray-800 hover:text-white' }}">
                Dashboard
            </a>

            @if($workPrefix)
                <div class="mt-2 border-t border-gray-800 pt-2 text-xs text-gray-400 px-3">WORK</div>

                <a href="{{ route($workPrefix.'rooms.index') }}"
                   class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                    Номера
                </a>
                <a href="{{ route($workPrefix.'room-types.index') }}"
                   class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                    Типы номеров
                </a>
                <a href="{{ route($workPrefix.'amenities.index') }}"
                   class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                    Удобства
                </a>
                <a href="{{ route($workPrefix.'services.index') }}"
                   class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                    Услуги
                </a>

                <div class="mt-2 border-t border-gray-800 pt-2"></div>
                <a href="{{ route($workPrefix.'bookings.index') }}"
                   class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                    Бронирования
                </a>
                <a href="{{ route($workPrefix.'clients.index') }}"
                   class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                    Клиенты
                </a>

                <div class="mt-2 border-t border-gray-800 pt-2"></div>
                <a href="{{ route($workPrefix.'invoices.index') }}"
                   class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                    Счета
                </a>
                <a href="{{ route($workPrefix.'payments.index') }}"
                   class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                    Оплаты
                </a>
                @if($isAdmin)
                    <a href="{{ route('admin.reports.index') }}"
                       class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                        Отчёты
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                        Персонал
                    </a>
                    <a href="{{ route('admin.audit-logs.index') }}"
                       class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                        Журнал действий
                    </a>
                @endif
            @endif

            @if(!$isStaffOrAdmin)
                <div class="mt-2 border-t border-gray-800 pt-2 text-xs text-gray-400 px-3">CLIENT</div>
                <a href="{{ route('my.bookings.index') }}"
                   class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                    Список заявок
                </a>
                <a href="{{ route('my.bookings.create') }}"
                   class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                    Подать заявку
                </a>
            @endif
        </div>

        <div class="pt-4 pb-3 border-t border-gray-800 px-4">
            @if($isAuth)
                <div class="text-gray-200 text-sm font-medium">{{ $u->name }}</div>
                <div class="text-gray-400 text-xs">{{ $u->email }}</div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}"
                       class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                        Профиль
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                            Выйти
                        </button>
                    </form>
                </div>
            @else
                <div class="text-gray-200 text-sm font-medium">Гость</div>
                <div class="mt-3 space-y-1">
                    <a href="{{ route('login') }}"
                       class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                        Register
                    </a>
                </div>
            @endif
        </div>
    </div>
</nav>