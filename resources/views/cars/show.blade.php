<x-app-layout>

    {{-- ===================== PRINTABLE FICHE TECHNIQUE ===================== --}}
    <div class="print-sheet">
        <div class="print-header">
            <div>
                <div class="print-logo">APEX<span style="color: #B8860B;"> CAR</span></div>
                <div style="font-size: 9pt; color: #888;">www.apexcar.ma | +212 766 153 755</div>
            </div>
            <div class="print-date">
                {{ __('car_detail.date') ?? 'Date' }}: {{ now()->format('d/m/Y') }}<br>
                {{ __('car_detail.ref') ?? 'Réf' }}: AXC-{{ str_pad($car->id, 5, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        <div class="print-brand">{{ $car->carModel->brand->name }}</div>
        <h1>{{ $car->carModel->name }} {{ $car->year }}</h1>

        <div class="print-price">{{ number_format($car->price) }} DH</div>

        <table>
            <tr>
                <th>{{ __('car_detail.brand') ?? 'Marque' }}</th>
                <td>{{ $car->carModel->brand->name }}</td>
            </tr>
            <tr>
                <th>{{ __('car_detail.model') ?? 'Modèle' }}</th>
                <td>{{ $car->carModel->name }}</td>
            </tr>
            <tr>
                <th>{{ __('car_detail.year') }}</th>
                <td>{{ $car->year }}</td>
            </tr>
            <tr>
                <th>{{ __('cars.fuel') }}</th>
                <td>{{ $car->fuel_type }}</td>
            </tr>
            <tr>
                <th>{{ __('cars.transmission') }}</th>
                <td>{{ $car->transmission }}</td>
            </tr>
            <tr>
                <th>{{ __('cars.mileage') }}</th>
                <td>{{ number_format($car->mileage) }} km</td>
            </tr>
            <tr>
                <th>{{ __('car_detail.color') }}</th>
                <td>{{ $car->color ?? '—' }}</td>
            </tr>
            <tr>
                <th>{{ __('car_detail.condition') }}</th>
                <td>{{ $car->condition === 'neuf' ? __('cars.new') : __('cars.used') }}</td>
            </tr>
            <tr>
                <th>{{ __('cars.status') ?? 'Statut' }}</th>
                <td>{{ $car->status === 'available' ? __('cars.available') : ($car->status === 'reserved' ? __('cars.reserved') : __('cars.sold')) }}</td>
            </tr>
        </table>

        @if($car->description)
            <div class="print-desc">
                <strong>{{ __('car_detail.description') }}:</strong><br>
                {{ $car->description }}
            </div>
        @endif

        @if($car->options->count())
            <h2>{{ __('car_detail.specs') }}</h2>
            <table>
                @foreach($car->options as $option)
                    <tr>
                        <th>{{ $option->name }}</th>
                        <td>+{{ number_format($option->price) }} DH</td>
                    </tr>
                @endforeach
            </table>
        @endif

        <div class="print-footer">
            <div>APEX CAR — Oujda, Maroc</div>
            <div>{{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    {{-- ===================== WEB CONTENT ===================== --}}
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                {{-- Car Images --}}
                <div class="space-y-6">
                    <div class="glass aspect-video overflow-hidden group">
                        @if($car->images && count($car->images) > 0)
                            <img src="{{ asset('storage/' . $car->images[0]) }}" alt="{{ $car->carModel->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <img src="{{ asset('images/car-placeholder.png') }}" alt="No image available" class="w-full h-full object-cover opacity-60">
                        @endif
                    </div>
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($car->images ?? [] as $index => $image)
                            @if($index > 0)
                                <div class="glass aspect-square overflow-hidden cursor-pointer hover:border-amber-500 transition-all">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Car Image {{ $index }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="glass p-8">
                        <h2 class="text-2xl font-bold mb-4">Description</h2>
                        <p class="text-gray-400 leading-relaxed">
                            {{ $car->description ?? 'No description provided for this vehicle.' }}
                        </p>
                    </div>
                </div>

                {{-- Car Details & Configuration --}}
                <div>
                    <div class="glass p-8 sticky top-24">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <div class="text-amber-500 font-bold uppercase tracking-wider mb-2">
                                    {{ $car->carModel->brand->name }}
                                </div>
                                <h1 class="text-4xl font-black mb-2">{{ $car->carModel->name }}</h1>
                                <p class="text-gray-400">{{ $car->year }} &bull; {{ $car->carModel->type }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-black text-amber-500">{{ number_format($car->price) }} DH</div>
                                <div class="text-sm text-gray-400">Fixed Price</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-8 mb-8 py-8 border-y border-white/5">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-600/10 border border-amber-600/20 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-gray-500 block text-xs font-bold uppercase tracking-widest mb-1">Kilométrage</span>
                                    <span class="text-lg font-black">{{ number_format($car->mileage) }} km</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-600/10 border border-amber-600/20 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022.547l-2.387 2.387a2 2 0 000 2.828l.586.586a2 2 0 002.828 0l2.387-2.387a2 2 0 00.547-1.022L21 16l-.572-.572a2 2 0 00-1.022.547z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21V5a2 2 0 012-2h2a2 2 0 012 2v4.672M12 13h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-gray-500 block text-xs font-bold uppercase tracking-widest mb-1">Carburant</span>
                                    <span class="text-lg font-black">{{ $car->fuel_type }}</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-600/10 border border-amber-600/20 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-gray-500 block text-xs font-bold uppercase tracking-widest mb-1">Boîte</span>
                                    <span class="text-lg font-black">{{ $car->transmission }}</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-600/10 border border-amber-600/20 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-gray-500 block text-xs font-bold uppercase tracking-widest mb-1">État</span>
                                    <span class="text-lg font-black text-green-400">{{ ucfirst($car->status) }}</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-600/10 border border-amber-600/20 flex items-center justify-center shrink-0">
                                    @if($car->condition === 'neuf')
                                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-gray-500 block text-xs font-bold uppercase tracking-widest mb-1">Condition</span>
                                    <span class="text-lg font-black {{ $car->condition === 'neuf' ? 'text-green-400' : 'text-yellow-400' }}">
                                        {{ $car->condition === 'neuf' ? 'Neuf' : 'Occasion' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Options / Configuration --}}
                        <div class="mb-8">
                            <h3 class="text-xl font-bold mb-4">Available Extras</h3>
                            <div class="space-y-3">
                                @forelse($car->options as $option)
                                    <div class="flex justify-between items-center p-4 bg-white/5 rounded-xl border border-white/10">
                                        <div class="font-medium">{{ $option->name }}</div>
                                        <div class="font-bold">+{{ number_format($option->price) }} DH</div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 italic">No standard extras listed.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex flex-col gap-4">
                            <a href="{{ route('cars.fiche', $car) }}" target="_blank" class="w-full bg-amber-600 hover:bg-amber-500 text-white font-bold py-4 rounded-xl text-center flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-amber-500/20 active:scale-95">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                {{ __('car_detail.print_sheet') ?? 'Imprimer Fiche Technique' }}
                            </a>
                            <a href="https://wa.me/212766153755?text={{ urlencode('Bonjour, je suis intéressé par le véhicule ' . $car->carModel->brand->name . ' ' . $car->carModel->name . ' (' . $car->year . ') affiché à ' . number_format($car->price) . ' DH.') }}" 
                               target="_blank" 
                               class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-4 rounded-xl text-center flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-green-500/20 active:scale-95">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.262 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.66.986 3.288 1.488 4.605 1.489 5.25-.001 9.522-4.275 9.525-9.53.002-2.546-.988-4.941-2.79-6.746a9.49 9.49 0 0 0-6.75-2.734C5.975 1.642 1.7 5.918 1.698 11.17c-.001 1.666.49 3.294 1.489 4.6l-1.077 3.93 4.037-1.056z"/>
                                </svg>
                                Contact WhatsApp
                            </a>
                            
                            <form action="{{ route('compare.add', $car) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full border border-white/20 hover:bg-white/10 py-3 rounded-xl font-bold transition-all">
                                    {{ __('cars.add_to_compare') ?? 'Add to Compare' }}
                                </button>
                            </form>
                        </div>

                        <div class="mt-6 text-center text-sm text-gray-500">
                            <p>Contact direct avec le vendeur via WhatsApp. Réponse rapide garantie.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
