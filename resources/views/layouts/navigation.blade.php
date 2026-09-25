<nav x-data="{ open: false, scrolled: false }"
     @scroll.window="scrolled = (window.scrollY > 10)"
     :class="scrolled ? 'bg-black/80 backdrop-blur-2xl shadow-2xl border-b border-white/10' : 'bg-transparent'"
     class="absolute top-0 left-0 right-0 z-50 transition-all duration-500">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="text-2xl font-black tracking-tighter text-white">
                APEX<span class="text-amber-500"> CAR</span>
            </a>

            {{-- Desktop nav links --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ url('/') }}"
                   class="text-sm font-semibold transition-colors {{ request()->is('/') ? 'text-amber-500' : 'text-gray-400 hover:text-white' }}">
                    {{ __('nav.home') }}
                </a>
                <a href="{{ route('cars.index') }}"
                   class="text-sm font-semibold transition-colors {{ request()->routeIs('cars.index') ? 'text-amber-500' : 'text-gray-400 hover:text-white' }}">
                    {{ __('nav.stock') }}
                </a>
                <a href="{{ route('cars.index', ['condition' => 'neuf']) }}"
                   class="text-sm font-semibold transition-colors text-gray-400 hover:text-white">
                    {{ __('nav.new') }}
                </a>
                <a href="{{ route('cars.index', ['condition' => 'occasion']) }}"
                   class="text-sm font-semibold transition-colors text-gray-400 hover:text-white">
                    {{ __('nav.used') }}
                </a>
                <a href="{{ route('cars.configurator') }}"
                   class="text-sm font-semibold transition-colors {{ request()->routeIs('cars.configurator') ? 'text-amber-500' : 'text-gray-400 hover:text-white' }}">
                    {{ __('nav.configurator') }}
                </a>
                @auth
                    <a href="{{ route('reservations.index') }}"
                       class="text-sm font-semibold transition-colors {{ request()->routeIs('reservations.index') ? 'text-amber-500' : 'text-gray-400 hover:text-white' }}">
                        {{ __('nav.my_reservations') }}
                    </a>
                    @if(Auth::user()->is_admin)
                        <a href="{{ url('/admin') }}"
                           class="text-sm font-semibold transition-colors {{ request()->is('admin*') ? 'text-amber-500' : 'text-gray-400 hover:text-white' }}">
                            {{ __('nav.dashboard') }}
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Auth area, Language switcher & Theme toggle --}}
            <div class="hidden md:flex items-center gap-3">
                {{-- Language Switcher --}}
                <div x-data="{ langOpen: false }" class="relative">
                    <button @click="langOpen = !langOpen" class="flex items-center gap-1.5 px-3 py-2 bg-white/5 hover:bg-white/10 rounded-xl text-sm font-semibold text-gray-400 hover:text-white transition-all border border-white/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <div x-show="langOpen" @click.away="langOpen = false" x-transition
                         class="absolute right-0 mt-2 w-36 bg-zinc-900 border border-white/10 rounded-xl shadow-2xl overflow-hidden" style="display:none">
                        <a href="?lang=fr" class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold {{ app()->getLocale() === 'fr' ? 'text-amber-500 bg-white/5' : 'text-gray-400 hover:text-white hover:bg-white/5' }} transition-colors">
                            <span class="text-lg">🇫🇷</span> Français
                        </a>
                        <a href="?lang=en" class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold {{ app()->getLocale() === 'en' ? 'text-amber-500 bg-white/5' : 'text-gray-400 hover:text-white hover:bg-white/5' }} transition-colors">
                            <span class="text-lg">🇬🇧</span> English
                        </a>
                        <a href="?lang=ar" class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold {{ app()->getLocale() === 'ar' ? 'text-amber-500 bg-white/5' : 'text-gray-400 hover:text-white hover:bg-white/5' }} transition-colors">
                            <span class="text-lg">🇸🇦</span> العربية
                        </a>
                    </div>
                </div>

                {{-- Theme Toggle --}}
                <button @click="isDark = !isDark" class="p-2 text-gray-400 hover:text-white transition-colors rounded-full hover:bg-white/5">
                    <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 rounded-xl text-sm font-semibold transition-all border border-white/10">
                                <div class="w-7 h-7 rounded-full bg-amber-600 flex items-center justify-center text-xs font-black">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('nav.profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('nav.logout') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm font-semibold text-gray-400 hover:text-white transition-colors">
                        {{ __('nav.login') }}
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-premium py-2 px-5 text-sm">
                            {{ __('nav.register') }}
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <button @click="open = !open" class="md:hidden p-2 text-gray-400 hover:text-white transition-colors">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" x-transition style="display:none"
         class="md:hidden bg-black/95 backdrop-blur-md border-t border-white/5 px-4 py-6 space-y-4">
        <a href="{{ url('/') }}" class="block text-sm font-semibold text-gray-400 hover:text-white py-2">{{ __('nav.home') }}</a>
        <a href="{{ route('cars.index') }}" class="block text-sm font-semibold text-gray-400 hover:text-white py-2">{{ __('nav.stock') }}</a>
        <a href="{{ route('cars.index', ['condition' => 'neuf']) }}" class="flex items-center gap-2 text-sm font-semibold text-gray-400 hover:text-white py-2">
            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg>
            {{ __('nav.new') }}
        </a>
        <a href="{{ route('cars.index', ['condition' => 'occasion']) }}" class="flex items-center gap-2 text-sm font-semibold text-gray-400 hover:text-white py-2">
            <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            {{ __('nav.used') }}
        </a>
        <a href="{{ route('cars.configurator') }}" class="flex items-center gap-2 text-sm font-semibold {{ request()->routeIs('cars.configurator') ? 'text-amber-500' : 'text-gray-400 hover:text-white' }} py-2">
            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            {{ __('nav.configurator') }}
        </a>
        {{-- Mobile Language Switcher --}}
        <div class="flex items-center gap-3 py-2 border-t border-white/5 pt-4">
            <a href="?lang=fr" class="px-3 py-1.5 rounded-lg text-sm font-semibold {{ app()->getLocale() === 'fr' ? 'bg-amber-600 text-white' : 'bg-white/5 text-gray-400 hover:text-white' }} transition-all">
                🇫🇷 FR
            </a>
            <a href="?lang=en" class="px-3 py-1.5 rounded-lg text-sm font-semibold {{ app()->getLocale() === 'en' ? 'bg-amber-600 text-white' : 'bg-white/5 text-gray-400 hover:text-white' }} transition-all">
                🇬🇧 EN
            </a>
            <a href="?lang=ar" class="px-3 py-1.5 rounded-lg text-sm font-semibold {{ app()->getLocale() === 'ar' ? 'bg-amber-600 text-white' : 'bg-white/5 text-gray-400 hover:text-white' }} transition-all">
                🇸🇦 AR
            </a>
        </div>
        @auth
            <a href="{{ route('reservations.index') }}" class="block text-sm font-semibold text-gray-400 hover:text-white py-2">{{ __('nav.my_reservations') }}</a>
            @if(Auth::user()->is_admin)
                <a href="{{ url('/admin') }}" class="block text-sm font-semibold text-gray-400 hover:text-white py-2">{{ __('nav.dashboard') }}</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left text-sm font-semibold text-red-500 hover:text-red-400 py-2">{{ __('nav.logout') }}</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="block text-sm font-semibold text-gray-400 hover:text-white py-2">{{ __('nav.login') }}</a>
            <a href="{{ route('register') }}" class="inline-block btn-premium py-2 px-5 text-sm">{{ __('nav.register') }}</a>
        @endauth
    </div>
</nav>
