@php
    $u = auth()->user();

    $isAuth  = auth()->check();
    $isAdmin = $u?->hasRole('admin') ?? false;
    $isStaff = $u?->hasRole('employee') ?? false;

    $workPrefix = $isAdmin ? 'admin.' : ($isStaff ? 'staff.' : null);
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Desktop -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    {{-- WORK AREA (admin OR employee) --}}
                    @if($workPrefix)
                        <x-nav-link :href="route($workPrefix.'bookings.index')" :active="request()->routeIs($workPrefix.'bookings.*')">Бронирования</x-nav-link>
                        <x-nav-link :href="route($workPrefix.'clients.index')" :active="request()->routeIs($workPrefix.'clients.*')">Клиенты</x-nav-link>
                        <x-nav-link :href="route($workPrefix.'room-types.index')" :active="request()->routeIs($workPrefix.'room-types.*')">Типы</x-nav-link>
                        <x-nav-link :href="route($workPrefix.'rooms.index')" :active="request()->routeIs($workPrefix.'rooms.*')">Номера</x-nav-link>
                        <x-nav-link :href="route($workPrefix.'amenities.index')" :active="request()->routeIs($workPrefix.'amenities.*')">Удобства</x-nav-link>
                        <x-nav-link :href="route($workPrefix.'services.index')" :active="request()->routeIs($workPrefix.'services.*')">Услуги</x-nav-link>
                        <x-nav-link :href="route($workPrefix.'invoices.index')" :active="request()->routeIs($workPrefix.'invoices.*')">Счета</x-nav-link>
                        <x-nav-link :href="route($workPrefix.'payments.index')" :active="request()->routeIs($workPrefix.'payments.*')">Оплаты</x-nav-link>
                    @endif

                    {{-- REPORTS: только админ --}}
                    @if($isAdmin)
                        <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">Отчёты</x-nav-link>
                    @endif

                    {{-- CLIENT AREA (доступно всем, даже гостю) --}}
                    <x-nav-link :href="route('my.bookings.index')" :active="request()->routeIs('my.bookings.*')">
                        Мои заявки
                    </x-nav-link>
                    <x-nav-link :href="route('my.bookings.create')" :active="request()->routeIs('my.bookings.create')">
                        Подать заявку
                    </x-nav-link>

                    {{-- ADMIN EXTRA --}}
                    @if($isAdmin)
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            Админ
                        </x-nav-link>

                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            Персонал
                        </x-nav-link>

                        <x-nav-link :href="route('admin.audit-logs.index')" :active="request()->routeIs('admin.audit-logs.*')">
                            Журнал
                        </x-nav-link>
                    @endif

                </div>
            </div>

            <!-- Right side -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if($isAuth)
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:text-gray-900 focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:underline">Login</a>
                        <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:underline">Register</a>
                    </div>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>

            @if($workPrefix)
                <x-responsive-nav-link :href="route($workPrefix.'bookings.index')" :active="request()->routeIs($workPrefix.'bookings.*')">Бронирования</x-responsive-nav-link>
                <x-responsive-nav-link :href="route($workPrefix.'clients.index')" :active="request()->routeIs($workPrefix.'clients.*')">Клиенты</x-responsive-nav-link>
                <x-responsive-nav-link :href="route($workPrefix.'room-types.index')" :active="request()->routeIs($workPrefix.'room-types.*')">Типы</x-responsive-nav-link>
                <x-responsive-nav-link :href="route($workPrefix.'rooms.index')" :active="request()->routeIs($workPrefix.'rooms.*')">Номера</x-responsive-nav-link>
                <x-responsive-nav-link :href="route($workPrefix.'amenities.index')" :active="request()->routeIs($workPrefix.'amenities.*')">Удобства</x-responsive-nav-link>
                <x-responsive-nav-link :href="route($workPrefix.'services.index')" :active="request()->routeIs($workPrefix.'services.*')">Услуги</x-responsive-nav-link>
                <x-responsive-nav-link :href="route($workPrefix.'invoices.index')" :active="request()->routeIs($workPrefix.'invoices.*')">Счета</x-responsive-nav-link>
                <x-responsive-nav-link :href="route($workPrefix.'payments.index')" :active="request()->routeIs($workPrefix.'payments.*')">Оплаты</x-responsive-nav-link>
            @endif

            @if($isAdmin)
                <x-responsive-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                    Отчёты
                </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('my.bookings.index')" :active="request()->routeIs('my.bookings.*')">
                Мои заявки
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('my.bookings.create')" :active="request()->routeIs('my.bookings.create')">
                Подать заявку
            </x-responsive-nav-link>

        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                @if($isAuth)
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                @else
                    <div class="font-medium text-base text-gray-800">Гость</div>
                @endif
            </div>

            <div class="mt-3 space-y-1">
                @if($isAuth)
                    <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Log Out
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