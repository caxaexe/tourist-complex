@php
    $u = auth()->user();
    $isAuth = auth()->check();

    $isAdmin = $isAuth ? $u->hasRole('admin') : false;
    $isStaff = $isAuth ? $u->isStaff() : false;
    $isStaffOrAdmin = $isAuth && ($isAdmin || $isStaff);

    $workPrefix = $isAdmin ? 'admin.' : ($isStaff ? 'staff.' : null);

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
        userMenu:false,
    }" class="sticky top-0 z-50 border-b border-gray-800/80 bg-gray-950/80 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- ЛЕВАЯ ЧАСТЬ: Логотип --}}
            <div class="flex items-center gap-1">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                    <span class="text-gray-200 font-semibold tracking-wide">
                        Castle Noctem
                    </span>
                </a>
            </div>

            {{-- ПРАВАЯ ЧАСТЬ: Навигация и профиль --}}
            <div class="hidden sm:flex sm:items-center sm:gap-2">

                @if(!$isStaffOrAdmin)
                    <a href="{{ route('public.rooms') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition
                       {{ request()->routeIs('public.rooms') ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800' }}">
                        {{ __('Номера и Удобства') }}
                    </a>
                    <a href="{{ route('public.services') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition
                       {{ request()->routeIs('public.services') ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800' }}">
                        {{ __('Услуги') }}
                    </a>
                    <a href="{{ route('my.bookings.index') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition
                       {{ request()->routeIs('my.bookings.*') ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800' }}">
                        {{ __('Мои заявки') }}
                    </a>
                @endif

                @if($workPrefix)
                    {{-- Дропдаун: Номера --}}
                    <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                        <button @click="dd=!dd" class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition" :class="dd ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800'">
                            {{ __('Номера') }} <span class="text-gray-400">▾</span>
                        </button>
                        <div x-show="dd" x-transition @click.outside="dd=false" class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                            <a href="{{ route($workPrefix.'room-types.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">{{ __('Типы номеров') }}</a>
                            <a href="{{ route($workPrefix.'rooms.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">{{ __('Номера') }}</a>
                            <a href="{{ route($workPrefix.'amenities.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">{{ __('Удобства') }}</a>
                            <a href="{{ route($workPrefix.'services.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">{{ __('Услуги') }}</a>
                        </div>
                    </div>

                    {{-- Дропдаун: Бронирование --}}
                    <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                        <button @click="dd=!dd" class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition" :class="dd ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800'">
                            {{ __('Бронирование') }} <span class="text-gray-400">▾</span>
                        </button>
                        <div x-show="dd" x-transition @click.outside="dd=false" class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                            <a href="{{ route($workPrefix.'bookings.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">{{ __('Бронирования') }}</a>
                            <a href="{{ route($workPrefix.'clients.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">{{ __('Клиенты') }}</a>
                        </div>
                    </div>

                    {{-- Дропдаун: Финансы --}}
                    <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                        <button @click="dd=!dd" class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition" :class="dd ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800'">
                            {{ __('Финансы') }} <span class="text-gray-400">▾</span>
                        </button>
                        <div x-show="dd" x-transition @click.outside="dd=false" class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                            <a href="{{ route($workPrefix.'invoices.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">{{ __('Счета') }}</a>
                            <a href="{{ route($workPrefix.'payments.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">{{ __('Оплаты') }}</a>
                            @if($isAdmin && Route::has('admin.reports.index'))
                                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">{{ __('Отчёты') }}</a>
                            @endif
                        </div>
                    </div>

                    {{-- Дропдаун: Администрирование --}}
                    @if($isAdmin)
                        <div class="relative" x-data="{ dd:false }" @keydown.escape.window="dd=false">
                            <button @click="dd=!dd" class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition" :class="dd ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800'">
                                {{ __('Администрирование') }} <span class="text-gray-400">▾</span>
                            </button>
                            <div x-show="dd" x-transition @click.outside="dd=false" class="absolute z-50 mt-2 w-64 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">{{ __('Персонал') }}</a>
                                <a href="{{ route('admin.audit-logs.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">{{ __('Журнал действий') }}</a>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="relative ml-2" x-data="{ langMenu: false }" @keydown.escape.window="langMenu=false">
                    <button @click="langMenu=!langMenu" class="inline-flex items-center gap-1 px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition uppercase">
                        {{ app()->getLocale() }} <span class="text-gray-400">▾</span>
                    </button>
                    <div x-show="langMenu" x-transition @click.outside="langMenu=false" class="absolute right-0 mt-2 w-32 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden z-50">
                        <a href="{{ route('lang.switch', 'ru') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white {{ app()->getLocale() === 'ru' ? 'bg-gray-800 text-white font-bold' : '' }}">Русский</a>
                        <a href="{{ route('lang.switch', 'ro') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white {{ app()->getLocale() === 'ro' ? 'bg-gray-800 text-white font-bold' : '' }}">Română</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white {{ app()->getLocale() === 'en' ? 'bg-gray-800 text-white font-bold' : '' }}">English</a>
                    </div>
                </div>

                {{-- Профиль пользователя --}}
                @if($isAuth)
                    <div class="relative ml-2" @keydown.escape.window="userMenu=false">
                        <button @click="userMenu=!userMenu"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition">
                            <span>{{ $u->name }}</span>
                            <span class="text-gray-400">▾</span>
                        </button>
                        <div x-show="userMenu" x-transition @click.outside="userMenu=false"
                             class="absolute right-0 mt-2 w-48 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">{{ __('Профиль') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                                    {{ __('Выйти') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="w-2 h-2 block opacity-0 cursor-default select-none" title=""></a>
                    @endif
                @endif
            </div>

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

    <div x-show="open" class="sm:hidden border-t border-gray-800">
        <div class="pt-2 pb-3 space-y-1 px-2">

            @if(!$isStaffOrAdmin)
                <a href="{{ route('public.rooms') }}"
                   class="block px-3 py-2 rounded-md text-sm font-medium transition
                   {{ request()->routeIs('public.rooms') ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800' }}">
                    {{ __('Номера и Удобства') }}
                </a>
                <a href="{{ route('public.services') }}"
                   class="block px-3 py-2 rounded-md text-sm font-medium transition
                   {{ request()->routeIs('public.services') ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800' }}">
                    {{ __('Услуги') }}
                </a>
                <a href="{{ route('my.bookings.index') }}"
                   class="block px-3 py-2 rounded-md text-sm font-medium transition
                   {{ request()->routeIs('my.bookings.*') ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800' }}">
                    {{ __('Мои заявки') }}
                </a>
            @endif

            @if($workPrefix)
                <div class="space-y-1">
                    <button @click="roomsOpen=!roomsOpen"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition">
                        <span>{{ __('Номера') }}</span><span class="text-gray-400" x-text="roomsOpen ? '▴' : '▾'"></span>
                    </button>
                    <div x-show="roomsOpen" class="pl-3 space-y-1">
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'room-types.index') }}">{{ __('Типы номеров') }}</a>
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'rooms.index') }}">{{ __('Номера') }}</a>
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'amenities.index') }}">{{ __('Удобства') }}</a>
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'services.index') }}">{{ __('Услуги') }}</a>
                    </div>
                </div>

                <div class="space-y-1">
                    <button @click="bookingOpen=!bookingOpen"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition">
                        <span>{{ __('Бронирование') }}</span><span class="text-gray-400" x-text="bookingOpen ? '▴' : '▾'"></span>
                    </button>
                    <div x-show="bookingOpen" class="pl-3 space-y-1">
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'bookings.index') }}">{{ __('Бронирования') }}</a>
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'clients.index') }}">{{ __('Клиенты') }}</a>
                    </div>
                </div>

                <div class="space-y-1">
                    <button @click="financeOpen=!financeOpen"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition">
                        <span>{{ __('Финансы') }}</span><span class="text-gray-400" x-text="financeOpen ? '▴' : '▾'"></span>
                    </button>
                    <div x-show="financeOpen" class="pl-3 space-y-1">
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'invoices.index') }}">{{ __('Счета') }}</a>
                        <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                           href="{{ route($workPrefix.'payments.index') }}">{{ __('Оплаты') }}</a>
                        @if($isAdmin && Route::has('admin.reports.index'))
                            <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                               href="{{ route('admin.reports.index') }}">{{ __('Отчёты') }}</a>
                        @endif
                    </div>
                </div>

                @if($isAdmin)
                    <div class="space-y-1">
                        <button @click="adminOpen=!adminOpen"
                                class="w-full flex items-center justify-between px-3 py-2 rounded-md text-left text-gray-200 hover:text-white hover:bg-gray-800 transition">
                            <span>{{ __('Администрирование') }}</span><span class="text-gray-400" x-text="adminOpen ? '▴' : '▾'"></span>
                        </button>
                        <div x-show="adminOpen" class="pl-3 space-y-1">
                            <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                               href="{{ route('admin.users.index') }}">{{ __('Персонал') }}</a>
                            <a class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                               href="{{ route('admin.audit-logs.index') }}">{{ __('Журнал действий') }}</a>
                        </div>
                    </div>
                @endif
            @endif

        </div>

        <div class="pt-4 pb-3 border-t border-gray-800 px-4">
            @if($isAuth)
                <div class="text-gray-200 font-medium">{{ $u->name }}</div>
                <div class="text-gray-400 text-sm">{{ $u->email }}</div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                        {{ __('Профиль') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                            {{ __('Выйти') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</nav>