@php
    $u = auth()->user();
    $isAdmin = $u?->hasRole('admin');
    $isStaff = $u?->hasAnyRole(['admin','employee']);
    $isUser  = $u?->hasRole('user');
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

                    {{-- STAFF (admin+employee) --}}
                    @if($isStaff)
                        <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">Бронирования</x-nav-link>
                        <x-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.*')">Клиенты</x-nav-link>
                        <x-nav-link :href="route('room-types.index')" :active="request()->routeIs('room-types.*')">Типы</x-nav-link>
                        <x-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')">Номера</x-nav-link>
                        <x-nav-link :href="route('amenities.index')" :active="request()->routeIs('amenities.*')">Удобства</x-nav-link>
                        <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">Услуги</x-nav-link>
                        <x-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">Счета</x-nav-link>
                        <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">Оплаты</x-nav-link>
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">Отчёты</x-nav-link>
                    @endif

                    {{-- USER --}}
                    @if($isUser)
                        <x-nav-link :href="route('my.bookings.index')" :active="request()->routeIs('my.bookings.*')">
                            Мои заявки
                        </x-nav-link>
                        <x-nav-link :href="route('my.bookings.create')" :active="request()->routeIs('my.bookings.create')">
                            Подать заявку
                        </x-nav-link>
                    @endif

                    {{-- ADMIN --}}
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

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
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

            @if($isStaff)
                <x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">Бронирования</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.*')">Клиенты</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('room-types.index')" :active="request()->routeIs('room-types.*')">Типы</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')">Номера</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('amenities.index')" :active="request()->routeIs('amenities.*')">Удобства</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">Услуги</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">Счета</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">Оплаты</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">Отчёты</x-responsive-nav-link>
            @endif

            @if($isUser)
                <x-responsive-nav-link :href="route('my.bookings.index')" :active="request()->routeIs('my.bookings.*')">
                    Мои заявки
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('my.bookings.create')" :active="request()->routeIs('my.bookings.create')">
                    Подать заявку
                </x-responsive-nav-link>
            @endif

            @if($isAdmin)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    Админ
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    Персонал
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.audit-logs.index')" :active="request()->routeIs('admin.audit-logs.*')">
                    Журнал
                </x-responsive-nav-link>
            @endif

        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profile
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
