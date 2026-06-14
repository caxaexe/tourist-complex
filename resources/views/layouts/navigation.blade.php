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

            {{-- ПРАВАЯ ЧАСТЬ: Навигация (десктоп) --}}
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
                    @foreach(['Номера' => 'roomsOpen', 'Бронирование' => 'bookingOpen', 'Финансы' => 'financeOpen'] as $label => $var)
                        <div class="relative" x-data="{ dd:false }">
                            <button @click="dd=!dd" class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition text-gray-200 hover:text-white hover:bg-gray-800">
                                {{ __($label) }} ▾
                            </button>
                            <div x-show="dd" x-transition @click.outside="dd=false" class="absolute z-50 mt-2 w-56 rounded-md border border-gray-800 bg-gray-900 shadow-lg overflow-hidden">
                                @if($label == 'Номера')
                                    <a href="{{ route($workPrefix.'room-types.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Типы номеров') }}</a>
                                    <a href="{{ route($workPrefix.'rooms.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Номера') }}</a>
                                    <a href="{{ route($workPrefix.'amenities.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Удобства') }}</a>
                                    <a href="{{ route($workPrefix.'services.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Услуги') }}</a>
                                @elseif($label == 'Бронирование')
                                    <a href="{{ route($workPrefix.'bookings.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Бронирования') }}</a>
                                    <a href="{{ route($workPrefix.'clients.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Клиенты') }}</a>
                                @else
                                    <a href="{{ route($workPrefix.'invoices.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Счета') }}</a>
                                    <a href="{{ route($workPrefix.'payments.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Оплаты') }}</a>
                                    @if($isAdmin && Route::has('admin.reports.index'))
                                        <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Отчёты') }}</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach

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

                {{-- Языки и Профиль (десктоп) --}}
                <div class="relative ml-2">
                    <button @click="langMenu=!langMenu" class="inline-flex items-center px-3 py-2 text-sm text-gray-200 uppercase"> {{ app()->getLocale() }} ▾ </button>
                    <div x-show="langMenu" @click.outside="langMenu=false" class="absolute right-0 mt-2 w-32 rounded-md bg-gray-900 border border-gray-800 z-50">
                        @foreach(['ru' => 'Русский', 'ro' => 'Română', 'en' => 'English'] as $code => $name)
                            <a href="{{ route('lang.switch', $code) }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ $name }}</a>
                        @endforeach
                    </div>
                </div>

                @if($isAuth)
                    <div class="relative ml-2">
                        <button @click="userMenu=!userMenu" class="inline-flex items-center px-3 py-2 text-sm text-gray-200"> {{ $u->name }} ▾ </button>
                        <div x-show="userMenu" @click.outside="userMenu=false" class="absolute right-0 mt-2 w-48 rounded-md bg-gray-900 border border-gray-800 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Профиль') }}</a>
                            <form method="POST" action="{{ route('logout') }}"> @csrf <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-800">{{ __('Выйти') }}</button></form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Мобильное меню (кнопка) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 text-gray-300">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{'hidden': open, 'inline-flex': !open }" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': !open, 'inline-flex': open }" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Выпадающее меню для мобильных --}}
    <div x-show="open" class="sm:hidden bg-gray-900 border-t border-gray-800">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @if(!$isStaffOrAdmin)
                <a href="{{ route('public.rooms') }}" class="block px-3 py-2 text-gray-200 hover:bg-gray-800">{{ __('Номера') }}</a>
                <a href="{{ route('public.services') }}" class="block px-3 py-2 text-gray-200 hover:bg-gray-800">{{ __('Услуги') }}</a>
                <a href="{{ route('my.bookings.index') }}" class="block px-3 py-2 text-gray-200 hover:bg-gray-800">{{ __('Мои заявки') }}</a>
            @else
                <a href="{{ route($workPrefix.'bookings.index') }}" class="block px-3 py-2 text-gray-200 hover:bg-gray-800">{{ __('Бронирования') }}</a>
                <a href="{{ route($workPrefix.'rooms.index') }}" class="block px-3 py-2 text-gray-200 hover:bg-gray-800">{{ __('Номера') }}</a>
            @endif

            <div class="border-t border-gray-700 mt-2 pt-2">
                <p class="px-3 text-xs text-gray-500 uppercase">{{ __('Язык') }}</p>
                @foreach(['ru' => 'Русский', 'ro' => 'Română', 'en' => 'English'] as $code => $name)
                    <a href="{{ route('lang.switch', $code) }}" class="block px-3 py-2 text-gray-200 hover:bg-gray-800">{{ $name }}</a>
                @endforeach
            </div>

            @if($isAuth)
                <div class="border-t border-gray-700 mt-2 pt-2">
                    <p class="px-3 text-xs text-gray-500 uppercase">{{ $u->name }}</p>
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-gray-200 hover:bg-gray-800">{{ __('Профиль') }}</a>
                    <form method="POST" action="{{ route('logout') }}"> @csrf <button type="submit" class="w-full text-left px-3 py-2 text-gray-200 hover:bg-gray-800">{{ __('Выйти') }}</button></form>
                </div>
            @endif
        </div>
    </div>
</nav>