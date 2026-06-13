@php
    $u = auth()->user();
    $isAuth = auth()->check();

    $isAdmin = $isAuth ? $u->hasRole('admin') : false;
    $isStaff = $isAuth ? $u->isStaff() : false;
    $isStaffOrAdmin = $isAuth && ($isAdmin || $isStaff);

    $workPrefix = $isAdmin ? 'admin.' : ($isStaff ? 'staff.' : null);
@endphp

<nav x-data="{
        open:false,
        roomsOpen:false,
        bookingOpen:false,
        financeOpen:false,
        adminOpen:false,
        userMenu:false,
        langMenu:false
    }" class="sticky top-0 z-50 border-b border-gray-800/80 bg-gray-950/80 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- ЛЕВАЯ ЧАСТЬ: Логотип --}}
            <div class="flex items-center gap-1">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                    <span class="text-gray-200 font-semibold tracking-wide">Castle Noctem</span>
                </a>
            </div>

            {{-- ПРАВАЯ ЧАСТЬ: Навигация --}}
            <div class="hidden sm:flex sm:items-center sm:gap-2">

                @if(!$isStaffOrAdmin)
                    <a href="{{ route('public.rooms') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('public.rooms') ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800' }}">
                        {{ __('Номера и Удобства') }}
                    </a>
                    <a href="{{ route('public.services') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('public.services') ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800' }}">
                        {{ __('Услуги') }}
                    </a>
                    <a href="{{ route('my.bookings.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('my.bookings.*') ? 'bg-gray-800 text-white' : 'text-gray-200 hover:text-white hover:bg-gray-800' }}">
                        {{ __('Мои заявки') }}
                    </a>
                @endif

                @if($workPrefix)
                    {{-- Дропдауны для персонала --}}
                    <div class="relative" x-data="{ dd:false }">
                        <button @click="dd=!dd" class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition text-gray-200 hover:text-white hover:bg-gray-800">
                            {{ __('Номера') }} ▾
                        </button>
                        <div x-show="dd" x-transition @click.outside="dd=false" class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                            <a href="{{ route($workPrefix.'room-types.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Типы номеров') }}</a>
                            <a href="{{ route($workPrefix.'rooms.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Номера') }}</a>
                            <a href="{{ route($workPrefix.'amenities.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Удобства') }}</a>
                            <a href="{{ route($workPrefix.'services.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Услуги') }}</a>
                        </div>
                    </div>

                    <div class="relative" x-data="{ dd:false }">
                        <button @click="dd=!dd" class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition text-gray-200 hover:text-white hover:bg-gray-800">
                            {{ __('Бронирование') }} ▾
                        </button>
                        <div x-show="dd" x-transition @click.outside="dd=false" class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                            <a href="{{ route($workPrefix.'bookings.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Бронирования') }}</a>
                            <a href="{{ route($workPrefix.'clients.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Клиенты') }}</a>
                        </div>
                    </div>

                    <div class="relative" x-data="{ dd:false }">
                        <button @click="dd=!dd" class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition text-gray-200 hover:text-white hover:bg-gray-800">
                            {{ __('Финансы') }} ▾
                        </button>
                        <div x-show="dd" x-transition @click.outside="dd=false" class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                            <a href="{{ route($workPrefix.'invoices.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Счета') }}</a>
                            <a href="{{ route($workPrefix.'payments.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Оплаты') }}</a>
                            @if($isAdmin && Route::has('admin.reports.index'))
                                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Отчёты') }}</a>
                            @endif
                        </div>
                    </div>

                    @if($isAdmin)
                        <div class="relative" x-data="{ dd:false }">
                            <button @click="dd=!dd" class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition text-gray-200 hover:text-white hover:bg-gray-800">
                                {{ __('Администрирование') }} ▾
                            </button>
                            <div x-show="dd" x-transition @click.outside="dd=false" class="absolute z-50 mt-2 w-64 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Персонал') }}</a>
                                <a href="{{ route('admin.audit-logs.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Журнал действий') }}</a>
                            </div>
                        </div>
                    @endif
                @endif

                {{-- ВЫНЕСЕНО: Языки видны всегда --}}
                <div class="relative ml-2" @keydown.escape.window="langMenu=false">
                    <button @click="langMenu=!langMenu" class="inline-flex items-center gap-1 px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition uppercase">
                        {{ app()->getLocale() }} ▾
                    </button>
                    <div x-show="langMenu" x-transition @click.outside="langMenu=false" class="absolute right-0 mt-2 w-32 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden z-50">
                        <a href="{{ route('lang.switch', 'ru') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">Русский</a>
                        <a href="{{ route('lang.switch', 'ro') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">Română</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">English</a>
                    </div>
                </div>

                {{-- Профиль --}}
                @if($isAuth)
                    <div class="relative ml-2" @keydown.escape.window="userMenu=false">
                        <button @click="userMenu=!userMenu" class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 transition">
                            <span>{{ $u->name }}</span> ▾
                        </button>
                        <div x-show="userMenu" x-transition @click.outside="userMenu=false" class="absolute right-0 mt-2 w-48 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Профиль') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Выйти') }}</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Мобильное меню --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    </nav>