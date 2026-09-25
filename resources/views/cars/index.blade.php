<x-app-layout>
    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            {{-- Page title --}}
            <div class="mb-8">
                <h1 class="text-3xl font-black mb-1">Notre Stock</h1>
                <p class="text-gray-500 text-sm">Parcourez notre sélection de véhicules neufs et d'occasion.</p>
            </div>

            <div class="flex flex-col md:flex-row gap-8">

                {{-- Filters Sidebar --}}
                <aside class="w-full md:w-64 shrink-0">
                    <div class="glass p-6 sticky top-28">
                        <h2 class="text-base font-black uppercase tracking-widest mb-6 text-gray-300">Filtres</h2>
                        <form action="{{ route('cars.index') }}" method="GET">

                            <div class="mb-5">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Marque</label>
                                <select name="brand" class="w-full bg-zinc-800 border border-black text-white rounded-lg px-4 py-2.5 text-sm focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Toutes les marques</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if(request('brand') && count($availableColors) > 0)
                                <div class="mb-5 animate-fade-in">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Couleur</label>
                                    <select name="color" class="w-full bg-zinc-800 border border-black text-white rounded-lg px-4 py-2.5 text-sm focus:ring-amber-500 focus:border-amber-500">
                                        <option value="">Toutes les couleurs</option>
                                        @foreach($availableColors as $color)
                                            <option value="{{ $color }}" {{ request('color') == $color ? 'selected' : '' }}>{{ $color }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="mb-5">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Condition</label>
                                <div class="flex gap-2">
                                    <a href="{{ route('cars.index', array_merge(request()->except('condition'), ['condition' => ''])) }}"
                                       class="flex-1 text-center text-xs font-bold py-2 rounded-lg transition-all {{ !request('condition') ? 'bg-amber-600 text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' }}">
                                        Tous
                                    </a>
                                    <a href="{{ route('cars.index', array_merge(request()->except('condition'), ['condition' => 'neuf'])) }}"
                                       class="flex-1 flex items-center justify-center gap-2 text-center text-xs font-bold py-2 rounded-lg transition-all {{ request('condition') == 'neuf' ? 'bg-green-600 text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg>
                                        Neuf
                                    </a>
                                    <a href="{{ route('cars.index', array_merge(request()->except('condition'), ['condition' => 'occasion'])) }}"
                                       class="flex-1 flex items-center justify-center gap-2 text-center text-xs font-bold py-2 rounded-lg transition-all {{ request('condition') == 'occasion' ? 'bg-yellow-600 text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Occasion
                                    </a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Prix (DH)</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                                           class="w-full bg-zinc-800 border border-white/10 rounded-lg px-3 py-2.5 text-sm text-white">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                                           class="w-full bg-zinc-800 border border-white/10 rounded-lg px-3 py-2.5 text-sm text-white">
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Carburant</label>
                                <select name="fuel_type" class="w-full bg-zinc-800 border border-black text-white rounded-lg px-4 py-2.5 text-sm focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Tous</option>
                                    @foreach(\App\Models\Car::where('status','available')->whereNotNull('fuel_type')->distinct()->pluck('fuel_type')->sort() as $fuel)
                                        <option value="{{ $fuel }}" {{ request('fuel_type') == $fuel ? 'selected' : '' }}>{{ $fuel }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-5">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Transmission</label>
                                <select name="transmission" class="w-full bg-zinc-800 border border-black text-white rounded-lg px-4 py-2.5 text-sm focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Toutes</option>
                                    @foreach(\App\Models\Car::where('status','available')->whereNotNull('transmission')->distinct()->pluck('transmission')->sort() as $t)
                                        <option value="{{ $t }}" {{ request('transmission') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="w-full btn-premium py-2.5 text-sm">Appliquer</button>
                            <a href="{{ route('cars.index') }}"
                               class="block text-center mt-3 text-xs text-gray-500 hover:text-amber-500 transition-colors">
                                Réinitialiser les filtres
                            </a>
                        </form>
                    </div>
                </aside>

                {{-- Car Grid --}}
                <main class="flex-1">
                    @if($cars->isEmpty())
                        <div class="glass p-20 text-center">
                            <div class="text-5xl mb-6">🚗</div>
                            <h3 class="text-2xl font-black mb-2">Aucun véhicule trouvé</h3>
                            <p class="text-gray-400 mb-6">Essayez de modifier vos filtres.</p>
                            <a href="{{ route('cars.index') }}" class="btn-premium">Voir tout le stock</a>
                        </div>
                    @else
                        <div class="flex items-center justify-between mb-6">
                            <div class="text-sm text-gray-500">
                                <span class="font-black text-white">{{ $cars->total() }}</span> véhicule(s) trouvé(s)
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($cars as $car)
                                @include('partials.car-card', ['car' => $car])
                            @endforeach
                        </div>
                        <div class="mt-12">
                            {{ $cars->links() }}
                        </div>
                    @endif
                </main>

            </div>
        </div>
    </div>
</x-app-layout>
