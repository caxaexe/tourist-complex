@php
    $u = auth()->user();
    $isAuth = auth()->check();

    $isAdmin = $u?->hasRole('admin') ?? false;
    $isStaff = $u?->hasRole('employee') ?? false;

    $isStaffOrAdmin = $isAuth && ($isAdmin || $isStaff);

    // admin.* / staff.* или null (клиент)
    $workPrefix = $isAdmin ? 'admin.' : ($isStaff ? 'staff.' : null);

    // Активность "Dashboard" для любых дашбордов
    $dashboardActive = request()->routeIs('dashboard')
        || request()->routeIs('admin.dashboard')
        || request()->routeIs('staff.dashboard')
        || request()->routeIs('client.dashboard');
@endphp

<nav
    x-data="{
        open:false,
        roomsOpen:false,
        bookingOpen:false,
        financeOpen:false,
        adminOpen:false,
        myOpen:false,
    }"
    class="bg-gray-950 border-b border-gray-800"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Left -->
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-200" />
                        <span class="text-gray-200 text-sm font-semibold hidden sm:inline">
                            {{ config('app.name', 'Laravel') }}
                        </span>
                    </a>
                </div>

                <!-- Desktop -->
                <div class="hidden sm:flex sm:items-center sm:ms-10 sm:space-x-2">

                    <x-nav-link :href="route('dashboard')" :active="$dashboardActive">
                        Dashboard
                    </x-nav-link>

                    {{-- WORK AREA (admin OR employee) --}}
                    @if($workPrefix)

                        {{-- Номера --}}
                        <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                            <button
                                @click="dd=!dd"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition"
                                :class="dd ? 'bg-gray-800 text-white' : ''"
                                type="button"
                            >
                                Номера
                                <svg class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                x-show="dd"
                                x-transition
                                @click.outside="dd=false"
                                class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden"
                            >
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
                            <button
                                @click="dd=!dd"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition"
                                :class="dd ? 'bg-gray-800 text-white' : ''"
                                type="button"
                            >
                                Бронирование
                                <svg class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                x-show="dd"
                                x-transition
                                @click.outside="dd=false"
                                class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden"
                            >
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
                            <button
                                @click="dd=!dd"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition"
                                :class="dd ? 'bg-gray-800 text-white' : ''"
                                type="button"
                            >
                                Финансы
                                <svg class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                x-show="dd"
                                x-transition
                                @click.outside="dd=false"
                                class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden"
                            >
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

                        {{-- Администрирование (только admin) --}}
                        @if($isAdmin)
                            <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                                <button
                                    @click="dd=!dd"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition"
                                    :class="dd ? 'bg-gray-800 text-white' : ''"
                                    type="button"
                                >
                                    Администрирование
                                    <svg class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div
                                    x-show="dd"
                                    x-transition
                                    @click.outside="dd=false"
                                    class="absolute z-50 mt-2 w-64 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden"
                                >
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

                    {{-- CLIENT AREA --}}
                    @if(!$isStaffOrAdmin)
                        <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                            <button
                                @click="dd=!dd"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition"
                                :class="dd ? 'bg-gray-800 text-white' : ''"
                                type="button"
                            >
                                Мои заявки
                                <svg class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                x-show="dd"
                                x-transition
                                @click.outside="dd=false"
                                class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden"
                            >
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

            <!-- Right side -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if($isAuth)
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">Профиль</x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Выйти
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm text-gray-200 hover:text-white hover:underline">Login</a>
                        <a href="{{ route('register') }}" class="text-sm text-gray-200 hover:text-white hover:underline">Register</a>
                    </div>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button
                    @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-800 focus:outline-none transition"
                >
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

            <x-responsive-nav-link :href="route('dashboard')" :active="$dashboardActive">
                Dashboard
            </x-responsive-nav-link>

            {{-- WORK AREA --}}
            @if($workPrefix)

                <!-- Номера -->
                <button
                    @click="roomsOpen=!roomsOpen"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition"
                    type="button"
                >
                    <span>Номера</span>
                    <svg class="h-4 w-4" :class="roomsOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="roomsOpen" class="pl-3 space-y-1">
                    <x-responsive-nav-link :href="route($workPrefix.'room-types.index')" :active="request()->routeIs($workPrefix.'room-types.*')">
                        Типы номеров
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route($workPrefix.'rooms.index')" :active="request()->routeIs($workPrefix.'rooms.*')">
                        Номера
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route($workPrefix.'amenities.index')" :active="request()->routeIs($workPrefix.'amenities.*')">
                        Удобства
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route($workPrefix.'services.index')" :active="request()->routeIs($workPrefix.'services.*')">
                        Услуги
                    </x-responsive-nav-link>
                </div>

                <!-- Бронирование -->
                <button
                    @click="bookingOpen=!bookingOpen"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition"
                    type="button"
                >
                    <span>Бронирование</span>
                    <svg class="h-4 w-4" :class="bookingOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="bookingOpen" class="pl-3 space-y-1">
                    <x-responsive-nav-link :href="route($workPrefix.'bookings.index')" :active="request()->routeIs($workPrefix.'bookings.*')">
                        Бронирования
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route($workPrefix.'clients.index')" :active="request()->routeIs($workPrefix.'clients.*')">
                        Клиенты
                    </x-responsive-nav-link>
                </div>

                <!-- Финансы -->
                <button
                    @click="financeOpen=!financeOpen"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition"
                    type="button"
                >
                    <span>Финансы</span>
                    <svg class="h-4 w-4" :class="financeOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="financeOpen" class="pl-3 space-y-1">
                    <x-responsive-nav-link :href="route($workPrefix.'invoices.index')" :active="request()->routeIs($workPrefix.'invoices.*')">
                        Счета
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route($workPrefix.'payments.index')" :active="request()->routeIs($workPrefix.'payments.*')">
                        Оплаты
                    </x-responsive-nav-link>

                    @if($isAdmin)
                        <x-responsive-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                            Отчёты
                        </x-responsive-nav-link>
                    @endif
                </div>

                <!-- Администрирование (admin) -->
                @if($isAdmin)
                    <button
                        @click="adminOpen=!adminOpen"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition"
                        type="button"
                    >
                        <span>Администрирование</span>
                        <svg class="h-4 w-4" :class="adminOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="adminOpen" class="pl-3 space-y-1">
                        <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            Персонал
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.audit-logs.index')" :active="request()->routeIs('admin.audit-logs.*')">
                            Журнал действий
                        </x-responsive-nav-link>
                    </div>
                @endif

            @endif

            {{-- CLIENT AREA --}}
            @if(!$isStaffOrAdmin)
                <button
                    @click="myOpen=!myOpen"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition"
                    type="button"
                >
                    <span>Мои заявки</span>
                    <svg class="h-4 w-4" :class="myOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="myOpen" class="pl-3 space-y-1">
                    <x-responsive-nav-link :href="route('my.bookings.index')" :active="request()->routeIs('my.bookings.index') || request()->routeIs('my.bookings.*')">
                        Список заявок
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('my.bookings.create')" :active="request()->routeIs('my.bookings.create')">
                        Подать заявку
                    </x-responsive-nav-link>
                </div>
            @endif

        </div>

        <!-- Mobile profile/logout -->
        <div class="pt-4 pb-1 border-t border-gray-800">
            <div class="px-4">
                @if($isAuth)
                    <div class="font-medium text-base text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                @else
                    <div class="font-medium text-base text-gray-200">Гость</div>
                @endif
            </div>

            <div class="mt-3 space-y-1 px-2">
                @if($isAuth)
                    <x-responsive-nav-link :href="route('profile.edit')">Профиль</x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Выйти
                        </x-responsive-nav-link>
                    </form>
                @else
                    <x-responsive-nav-link :href="route('login')">Login</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">Register</x-responsive-nav-link>
                @endif
            </div>
        </div>
    </div>
</nav>