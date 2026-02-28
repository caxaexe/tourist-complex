@php
    $u = auth()->user();
    $isAuth = auth()->check();

    // ВАЖНО: у тебя роль staff (НЕ employee)
    $isAdmin = $isAuth ? $u->hasRole('admin') : false;
    $isStaff = $isAuth ? $u->hasRole('staff') : false;
    $isStaffOrAdmin = $isAuth && ($isAdmin || $isStaff);

    $workPrefix = $isAdmin ? 'admin.' : ($isStaff ? 'staff.' : null);

    $dashboardUrl = $isStaffOrAdmin ? route('dashboard') : route('my.bookings.index');

    $isActivePrefix = function (string $prefix) {
        return request()->routeIs($prefix . '*');
    };
@endphp

<nav x-data="{
        open:false,
        roomsOpen:false,
        bookingOpen:false,
        financeOpen:false,
        adminOpen:false,
        myOpen:false,
        userMenu:false,
    }" class="bg-gray-950 border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Left -->
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2">
                    <span class="text-gray-200 font-semibold">
                        {{ config('app.name', 'App') }}
                    </span>
                </a>

                <!-- Desktop menu -->
                <div class="hidden sm:flex sm:items-center sm:space-x-2">

                    <a href="{{ $dashboardUrl }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition
                       {{ (request()->routeIs('dashboard') || request()->routeIs('my.bookings.*')) ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800' }}">
                        Dashboard
                    </a>

                    @if($workPrefix)
                        <!-- Rooms dropdown -->
                        <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                            <button @click="dd=!dd"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition"
                                :class="dd ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800'">
                                Номера
                                <span class="text-gray-400">▾</span>
                            </button>

                            <div x-show="dd" x-transition @click.outside="dd=false"
                                 class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
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

                        <!-- Booking dropdown -->
                        <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                            <button @click="dd=!dd"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition"
                                :class="dd ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800'">
                                Бронирование
                                <span class="text-gray-400">▾</span>
                            </button>

                            <div x-show="dd" x-transition @click.outside="dd=false"
                                 class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
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

                        <!-- Finance dropdown -->
                        <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                            <button @click="dd=!dd"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition"
                                :class="dd ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800'">
                                Финансы
                                <span class="text-gray-400">▾</span>
                            </button>

                            <div x-show="dd" x-transition @click.outside="dd=false"
                                 class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                                <a href="{{ route($workPrefix.'invoices.index') }}"
                                   class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    Счета
                                </a>
                                <a href="{{ route($workPrefix.'payments.index') }}"
                                   class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    Оплаты
                                </a>
                                @if($isAdmin && Route::has('admin.reports.index'))
                                    <a href="{{ route('admin.reports.index') }}"
                                       class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                        Отчёты
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Admin dropdown -->
                        @if($isAdmin)
                            <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                                <button @click="dd=!dd"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition"
                                    :class="dd ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800'">
                                    Администрирование
                                    <span class="text-gray-400">▾</span>
                                </button>

                                <div x-show="dd" x-transition @click.outside="dd=false"
                                     class="absolute z-50 mt-2 w-64 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
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

                    @if(!$isStaffOrAdmin)
                        <!-- Client dropdown -->
                        <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                            <button @click="dd=!dd"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition"
                                :class="dd ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800'">
                                Мои заявки
                                <span class="text-gray-400">▾</span>
                            </button>

                            <div x-show="dd" x-transition @click.outside="dd=false"
                                 class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
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
                    <div class="relative" @keydown.escape.window="userMenu=false">
                        <button @click="userMenu=!userMenu"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition">
                            <span>{{ $u->name }}</span>
                            <span class="text-gray-400">▾</span>
                        </button>

                        <div x-show="userMenu" x-transition @click.outside="userMenu=false"
                             class="absolute right-0 mt-2 w-48 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
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
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" class="sm:hidden border-t border-gray-800">
        <div class="pt-2 pb-3 space-y-1 px-2">

            <a href="{{ $dashboardUrl }}"
               class="block px-3 py-2 rounded-md text-sm font-medium transition
               {{ (request()->routeIs('dashboard') || request()->routeIs('my.bookings.*')) ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800' }}">
                Dashboard
            </a>

            @if($workPrefix)
                <div class="space-y-1">
                    <button @click="roomsOpen=!roomsOpen"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition">
                        <span>Номера</span><span class="text-gray-400" x-text="roomsOpen ? '▴' : '▾'"></span>
                    </button>
                    <div x-show="roomsOpen" class="pl-3 space-y-1">
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'room-types.index') }}">Типы номеров</a>
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'rooms.index') }}">Номера</a>
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'amenities.index') }}">Удобства</a>
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'services.index') }}">Услуги</a>
                    </div>
                </div>

                <div class="space-y-1">
                    <button @click="bookingOpen=!bookingOpen"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition">
                        <span>Бронирование</span><span class="text-gray-400" x-text="bookingOpen ? '▴' : '▾'"></span>
                    </button>
                    <div x-show="bookingOpen" class="pl-3 space-y-1">
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'bookings.index') }}">Бронирования</a>
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'clients.index') }}">Клиенты</a>
                    </div>
                </div>

                <div class="space-y-1">
                    <button @click="financeOpen=!financeOpen"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition">
                        <span>Финансы</span><span class="text-gray-400" x-text="financeOpen ? '▴' : '▾'"></span>
                    </button>
                    <div x-show="financeOpen" class="pl-3 space-y-1">
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'invoices.index') }}">Счета</a>
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'payments.index') }}">Оплаты</a>
                        @if($isAdmin && Route::has('admin.reports.index'))
                            <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                               href="{{ route('admin.reports.index') }}">Отчёты</a>
                        @endif
                    </div>
                </div>

                @if($isAdmin)
                    <div class="space-y-1">
                        <button @click="adminOpen=!adminOpen"
                                class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition">
                            <span>Администрирование</span><span class="text-gray-400" x-text="adminOpen ? '▴' : '▾'"></span>
                        </button>
                        <div x-show="adminOpen" class="pl-3 space-y-1">
                            <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                               href="{{ route('admin.users.index') }}">Персонал</a>
                            <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                               href="{{ route('admin.audit-logs.index') }}">Журнал действий</a>
                        </div>
                    </div>
                @endif
            @endif

            @if(!$isStaffOrAdmin)
                <div class="space-y-1">
                    <button @click="myOpen=!myOpen"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition">
                        <span>Мои заявки</span><span class="text-gray-400" x-text="myOpen ? '▴' : '▾'"></span>
                    </button>
                    <div x-show="myOpen" class="pl-3 space-y-1">
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route('my.bookings.index') }}">Список заявок</a>
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route('my.bookings.create') }}">Подать заявку</a>
                    </div>
                </div>
            @endif

        </div>

        <div class="pt-4 pb-3 border-t border-gray-800 px-4">
            @if($isAuth)
                <div class="text-gray-200 font-medium">{{ $u->name }}</div>
                <div class="text-gray-400 text-sm">{{ $u->email }}</div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                        Профиль
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                            Выйти
                        </button>
                    </form>
                </div>
            @else
                <div class="space-y-1">
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">Register</a>
                </div>
            @endif
        </div>
    </div>
</nav>