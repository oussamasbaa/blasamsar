<x-app-layout>

    {{-- ===================== HERO SECTION ===================== --}}
    <section class="relative min-h-[70vh] flex items-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero_car.png') }}"
                 alt="Hero Car"
                 class="w-full h-full object-cover hero-zoom opacity-40">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-[#0a0a0a]"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center animate-fade-in">
            <span class="section-accent text-lg">{{ __('hero.discover') }}</span>
            <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight">
                APEX<span class="text-amber-600"> CAR</span>
            </h1>
            <p class="text-lg text-gray-400 max-w-2xl mx-auto mb-10">
                {{ __('hero.title') }}
            </p>

            <div class="flex justify-center gap-3 mb-10">
                <a href="{{ route('cars.index', ['condition' => 'neuf']) }}"
                   class="tab-btn active flex items-center gap-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg>
                    {{ __('cars.new') }}
                </a>
                <a href="{{ route('cars.index', ['condition' => 'occasion']) }}"
                   class="tab-btn flex items-center gap-2">
                    <svg class="w-5 h-5 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ __('cars.used') }}
                </a>
            </div>

            <a href="{{ route('cars.index') }}" class="btn-premium text-base px-10 py-4">
                {{ __('hero.explore') }}
            </a>
        </div>
    </section>

    {{-- ===================== STATS BAR ===================== --}}
    <section class="border-y border-white/5 bg-zinc-900/50 py-6">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-0 divide-x divide-white/5 text-center">
            <div class="py-4 px-6">
                <div class="text-3xl font-black text-amber-500">500+</div>
                <div class="text-gray-500 text-sm mt-1">{{ __('home.stats_vehicles') }}</div>
            </div>
            <div class="py-4 px-6">
                <div class="text-3xl font-black text-amber-500">50+</div>
                <div class="text-gray-500 text-sm mt-1">{{ __('home.stats_brands') }}</div>
            </div>
            <div class="py-4 px-6">
                <div class="text-3xl font-black text-amber-500">100%</div>
                <div class="text-gray-500 text-sm mt-1">{{ __('home.stats_clients') }}</div>
            </div>
            <div class="py-4 px-6">
                <div class="text-3xl font-black text-amber-500">24/7</div>
                <div class="text-gray-500 text-sm mt-1">{{ __('home.stats_years') }}</div>
            </div>
        </div>
    </section>

    {{-- ===================== FEATURED CARS ===================== --}}
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-4">
                <span class="section-accent">{{ __('hero.discover') }}</span>
                <h2 class="section-title">{{ __('home.featured_title') }}</h2>

                @php
                    $newCars = \App\Models\Car::with('carModel.brand')->where('condition','neuf')->latest()->take(3)->get();
                    $usedCars = \App\Models\Car::with('carModel.brand')->where('condition','occasion')->latest()->take(3)->get();
                    $featuredCars = \App\Models\Car::with('carModel.brand')->latest()->take(3)->get();
                @endphp
            </div>

            <div x-data="{ tab: 'all' }">
                <div class="flex justify-center gap-3 mb-10">
                    <button @click="tab='all'"
                            :class="tab==='all' ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/30' : 'bg-white/10 text-gray-300 hover:bg-white/20'"
                            class="tab-btn">
                        {{ __('cars.all_conditions') }}
                    </button>
                    <button @click="tab='neuf'"
                            :class="tab==='neuf' ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/30' : 'bg-white/10 text-gray-300 hover:bg-white/20'"
                            class="tab-btn flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg>
                        {{ __('cars.new') }}
                    </button>
                    <button @click="tab='occasion'"
                            :class="tab==='occasion' ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/30' : 'bg-white/10 text-gray-300 hover:bg-white/20'"
                            class="tab-btn flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        {{ __('cars.used') }}
                    </button>
                </div>

                <div x-show="tab==='all'" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($featuredCars as $car)
                        @include('partials.car-card', ['car' => $car])
                    @endforeach
                </div>

                <div x-show="tab==='neuf'" style="display:none" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @forelse ($newCars as $car)
                        @include('partials.car-card', ['car' => $car])
                    @empty
                        <div class="col-span-3 text-center text-gray-500 py-12">{{ __('cars.no_results') }}</div>
                    @endforelse
                </div>

                <div x-show="tab==='occasion'" style="display:none" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @forelse ($usedCars as $car)
                        @include('partials.car-card', ['car' => $car])
                    @empty
                        <div class="col-span-3 text-center text-gray-500 py-12">{{ __('cars.no_results') }}</div>
                    @endforelse
                </div>

                <div class="text-center mt-12">
                    <a href="{{ route('cars.index') }}" class="btn-premium px-10 py-4 text-base">
                        {{ __('home.view_all') }} &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== WHY APEX CAR ===================== --}}
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-zinc-900/30">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-3xl md:text-5xl font-black mb-16">
                {{ __('home.why_title') }} <span class="text-amber-500">APEX CAR</span> ?
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="why-card group">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-amber-600/10 border border-amber-600/20 flex items-center justify-center group-hover:bg-amber-600/20 transition-all">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black mb-3">{{ __('home.quality_title') }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ __('home.quality_desc') }}</p>
                </div>
                <div class="why-card group">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-amber-600/10 border border-amber-600/20 flex items-center justify-center group-hover:bg-amber-600/20 transition-all">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black mb-3">{{ __('home.best_price_title') }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ __('home.best_price_desc') }}</p>
                </div>
                <div class="why-card group">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-amber-600/10 border border-amber-600/20 flex items-center justify-center group-hover:bg-amber-600/20 transition-all">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black mb-3">{{ __('home.finance_title') }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ __('home.finance_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== POPULAR BRANDS ===================== --}}
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="section-title">{{ __('home.stats_brands') }}</h2>
            </div>

            @php
                $brands = \App\Models\Brand::withCount('carModels')->get();
            @endphp

            <div class="flex flex-wrap justify-center gap-4 mb-12">
                @foreach($brands as $brand)
                    <a href="{{ route('cars.index', ['brand' => $brand->id]) }}"
                       class="flex flex-col items-center gap-2 px-8 py-5 glass hover:border-amber-600/30 transition-all hover:-translate-y-1 group">
                        <div class="text-2xl font-black group-hover:text-amber-500 transition-colors">
                            {{ $brand->name }}
                        </div>
                        <div class="text-xs text-gray-500">{{ $brand->car_models_count }} {{ __('home.stats_brands') }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== CTA ===================== --}}
    <section class="py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-amber-900/30 via-amber-800/10 to-amber-900/30 border-y border-amber-600/10">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-black mb-4">{{ __('home.cta_title') }}</h2>
            <p class="text-gray-400 mb-8">{{ __('home.cta_subtitle') }}</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('cars.index', ['condition' => 'neuf']) }}" class="btn-premium px-8 py-4 text-base flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg>
                    {{ __('cars.new') }}
                </a>
                <a href="{{ route('cars.index', ['condition' => 'occasion']) }}" class="btn-outline px-8 py-4 text-base flex items-center gap-2 text-white border-white/20 hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ __('cars.used') }}
                </a>
            </div>
        </div>
    </section>

</x-app-layout>
