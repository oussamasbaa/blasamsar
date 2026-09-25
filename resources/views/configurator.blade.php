<x-app-layout>
    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            <div class="mb-10 text-center">
                <span class="section-accent">{{ __('config.title_badge') }}</span>
                <h1 class="text-3xl md:text-4xl font-black mb-3">{{ __('config.title') }}</h1>
                <p class="text-gray-500 text-sm max-w-xl mx-auto">{{ __('config.subtitle') }}</p>
            </div>

            <div x-data="configurator()" class="space-y-6">

                {{-- Step 1: Brand --}}
                <div class="glass p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-amber-600/10 border border-amber-600/20 flex items-center justify-center text-amber-500 font-black text-sm">1</div>
                        <h2 class="text-lg font-black">{{ __('config.brand') }}</h2>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @foreach($brands as $brand)
                            <button type="button"
                                    @click="selectBrand({{ $brand->id }}, '{{ $brand->name }}')"
                                    :class="form.brand_id == {{ $brand->id }} ? 'border-amber-500 bg-amber-500/10 text-white' : 'border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:text-white'"
                                    class="flex flex-col items-center gap-2 p-4 rounded-xl border transition-all text-center group">
                                <div class="text-sm font-black group-hover:text-amber-500 transition-colors">{{ $brand->name }}</div>
                                <div class="text-xs text-gray-600">{{ $brand->car_models_count }} {{ __('config.models') }}</div>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Step 2: Model --}}
                <div class="glass p-6" x-show="form.brand_id" x-transition>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-amber-600/10 border border-amber-600/20 flex items-center justify-center text-amber-500 font-black text-sm">2</div>
                        <h2 class="text-lg font-black">{{ __('config.model') }}</h2>
                    </div>
                    <div x-show="loadingModels" class="text-center py-8">
                        <div class="inline-block w-6 h-6 border-2 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <div x-show="!loadingModels && models.length === 0" class="text-center py-8 text-gray-500 text-sm">
                        {{ __('config.no_models') }}
                    </div>
                    <div x-show="!loadingModels && models.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        <template x-for="m in models" :key="m.id">
                            <button type="button"
                                    @click="selectModel(m.id, m.name)"
                                    :class="form.model_id == m.id ? 'border-amber-500 bg-amber-500/10 text-white' : 'border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:text-white'"
                                    class="flex flex-col items-center gap-1 p-4 rounded-xl border transition-all text-center">
                                <div class="text-sm font-black" x-text="m.name"></div>
                                <div class="text-xs text-gray-600" x-text="m.cars_count + ' {{ strtolower(__('config.available')) }}'"></div>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Step 3: Details --}}
                <div class="glass p-6" x-show="form.model_id" x-transition>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-amber-600/10 border border-amber-600/20 flex items-center justify-center text-amber-500 font-black text-sm">3</div>
                        <h2 class="text-lg font-black">{{ __('config.details') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                        {{-- Fuel --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('config.fuel') }}</label>
                            <select x-model="form.fuel_type"
                                    class="w-full bg-zinc-800 border border-white/10 text-white rounded-lg px-4 py-2.5 text-sm focus:ring-amber-500 focus:border-amber-500">
                                <option value="">{{ __('config.all_fuel') }}</option>
                                @foreach($fuelTypes as $fuel)
                                    <option value="{{ $fuel }}">{{ $fuel }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Transmission --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('config.transmission') }}</label>
                            <select x-model="form.transmission"
                                    class="w-full bg-zinc-800 border border-white/10 text-white rounded-lg px-4 py-2.5 text-sm focus:ring-amber-500 focus:border-amber-500">
                                <option value="">{{ __('config.all_transmissions') }}</option>
                                @foreach($transmissions as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Condition --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('config.condition') }}</label>
                            <div class="flex gap-2">
                                <button type="button" @click="form.condition = ''"
                                        :class="form.condition === '' ? 'bg-amber-600 text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10'"
                                        class="flex-1 text-center text-xs font-bold py-2.5 rounded-lg transition-all">
                                    {{ __('config.all') }}
                                </button>
                                @foreach($conditions as $c)
                                    <button type="button" @click="form.condition = '{{ $c }}'"
                                            :class="form.condition === '{{ $c }}' ? '{{ $c === 'neuf' ? 'bg-green-600' : 'bg-yellow-600' }} text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10'"
                                            class="flex-1 text-center text-xs font-bold py-2.5 rounded-lg transition-all capitalize">
                                        {{ $c }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Color --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('config.color') }}</label>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="form.color = ''"
                                        :class="form.color === '' ? 'ring-2 ring-amber-500 bg-white/10' : 'bg-white/5 hover:bg-white/10'"
                                        class="w-8 h-8 rounded-full border border-white/20 flex items-center justify-center text-xs text-gray-400 transition-all" title="{{ __('config.all') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                @foreach($allColors as $color)
                                    @php
                                        $colorMap = [
                                            'Noir' => '#1a1a1a', 'Black' => '#1a1a1a', 'Noir' => '#1a1a1a',
                                            'Blanc' => '#ffffff', 'White' => '#ffffff',
                                            'Gris' => '#6b7280', 'Gray' => '#6b7280', 'Grey' => '#6b7280',
                                            'Rouge' => '#dc2626', 'Red' => '#dc2626',
                                            'Bleu' => '#2563eb', 'Blue' => '#2563eb',
                                            'Vert' => '#16a34a', 'Green' => '#16a34a',
                                            'Argent' => '#9ca3af', 'Silver' => '#9ca3af',
                                            'Or' => '#d97706', 'Gold' => '#d97706', 'Doré' => '#d97706',
                                            'Gris Anthracite' => '#374151',
                                        ];
                                        $hex = $colorMap[$color] ?? '#6b7280';
                                    @endphp
                                    <button type="button" @click="form.color = '{{ $color }}'"
                                            :class="form.color === '{{ $color }}' ? 'ring-2 ring-amber-500 scale-110' : 'hover:scale-110'"
                                            class="w-8 h-8 rounded-full border-2 border-white/20 transition-all"
                                            style="background-color: {{ $hex }}"
                                            title="{{ $color }}">
                                    </button>
                                @endforeach
                            </div>
                            <div x-show="form.color" class="mt-2 text-xs text-gray-400" x-text="form.color"></div>
                        </div>

                        {{-- Price Range --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('config.price_range') }}</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="number" x-model="form.min_price" placeholder="{{ __('config.min') }}"
                                       class="w-full bg-zinc-800 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white placeholder-gray-600">
                                <input type="number" x-model="form.max_price" placeholder="{{ __('config.max') }}"
                                       class="w-full bg-zinc-800 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white placeholder-gray-600">
                            </div>
                            @if($priceRange)
                                <div class="mt-1 text-xs text-gray-600">
                                    {{ __('config.database_range') }}: {{ number_format($priceRange->min_price, 0, ',', ' ') }} - {{ number_format($priceRange->max_price, 0, ',', ' ') }} DH
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Search Button --}}
                <div x-show="form.brand_id" x-transition class="flex flex-col sm:flex-row gap-3 justify-center pt-4 pb-8">
                    <button @click="search()"
                            class="btn-premium px-12 py-4 text-base flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        {{ __('config.search') }} <span x-text="matchCount" x-show="matchCount !== null" class="text-amber-300"></span>
                    </button>
                    <button @click="reset()"
                            class="px-8 py-4 text-sm font-bold text-gray-500 hover:text-amber-500 border border-white/10 hover:border-amber-500/30 rounded-xl transition-all">
                        {{ __('config.reset') }}
                    </button>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function configurator() {
            return {
                form: {
                    brand_id: null,
                    brand_name: '',
                    model_id: null,
                    model_name: '',
                    fuel_type: '',
                    transmission: '',
                    condition: '',
                    color: '',
                    min_price: '',
                    max_price: '',
                },
                models: [],
                loadingModels: false,
                matchCount: null,

                async selectBrand(id, name) {
                    if (this.form.brand_id === id) {
                        this.form.brand_id = null;
                        this.form.brand_name = '';
                        this.form.model_id = null;
                        this.form.model_name = '';
                        this.models = [];
                        this.matchCount = null;
                        return;
                    }
                    this.form.brand_id = id;
                    this.form.brand_name = name;
                    this.form.model_id = null;
                    this.form.model_name = '';
                    this.models = [];
                    this.matchCount = null;
                    this.loadingModels = true;
                    try {
                        const res = await fetch(`{{ route('api.models') }}?brand_id=${id}`);
                        this.models = await res.json();
                    } catch (e) {
                        this.models = [];
                    }
                    this.loadingModels = false;
                },

                selectModel(id, name) {
                    if (this.form.model_id === id) {
                        this.form.model_id = null;
                        this.form.model_name = '';
                        this.matchCount = null;
                        return;
                    }
                    this.form.model_id = id;
                    this.form.model_name = name;
                    this.matchCount = null;
                },

                buildUrl() {
                    const params = new URLSearchParams();
                    if (this.form.brand_id) params.set('brand', this.form.brand_id);
                    if (this.form.model_id) params.set('model', this.form.model_id);
                    if (this.form.fuel_type) params.set('fuel_type', this.form.fuel_type);
                    if (this.form.transmission) params.set('transmission', this.form.transmission);
                    if (this.form.condition) params.set('condition', this.form.condition);
                    if (this.form.color) params.set('color', this.form.color);
                    if (this.form.min_price) params.set('min_price', this.form.min_price);
                    if (this.form.max_price) params.set('max_price', this.form.max_price);
                    return `{{ route('cars.index') }}?${params.toString()}`;
                },

                search() {
                    window.location.href = this.buildUrl();
                },

                reset() {
                    this.form = {
                        brand_id: null, brand_name: '', model_id: null, model_name: '',
                        fuel_type: '', transmission: '', condition: '', color: '',
                        min_price: '', max_price: '',
                    };
                    this.models = [];
                    this.matchCount = null;
                },
            }
        }
    </script>
    @endpush
</x-app-layout>
