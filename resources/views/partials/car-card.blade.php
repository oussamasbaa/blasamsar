{{-- Premium Car Card Partial - inspired by luxuryauto.ma --}}
<div class="car-card-dark group">
    {{-- Image --}}
    {{-- Image Swiper --}}
    <div class="relative overflow-hidden aspect-video bg-zinc-800" 
         x-data="{ swiper: null }" 
         x-init="
            swiper = new Swiper($refs.container, {
                loop: {{ $car->images && count($car->images) > 1 ? 'true' : 'false' }},
                pagination: {
                    el: $refs.pagination,
                    clickable: true,
                    dynamicBullets: true,
                },
                navigation: {
                    nextEl: $refs.next,
                    prevEl: $refs.prev,
                },
                grabCursor: true,
                effect: 'slide'
            })
         ">
        
        <div x-ref="container" class="swiper h-full w-full">
            <div class="swiper-wrapper">
                @if($car->images && count($car->images) > 0)
                    @foreach($car->images as $index => $image)
                        <div class="swiper-slide h-full">
                            <img src="{{ asset('storage/' . $image) }}"
                                 alt="{{ $car->carModel->name }} - Image {{ $index + 1 }}"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                 loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                        </div>
                    @endforeach
                @else
                    <div class="swiper-slide h-full flex items-center justify-center">
                        <img src="{{ asset('images/car-placeholder.png') }}"
                             alt="No image"
                             class="w-full h-full object-cover opacity-60">
                    </div>
                @endif
            </div>

            @if($car->images && count($car->images) > 1)
                {{-- Navigation --}}
                <div x-ref="prev" class="swiper-button-prev !opacity-0 group-hover:!opacity-100 !transition-opacity !duration-300"></div>
                <div x-ref="next" class="swiper-button-next !opacity-0 group-hover:!opacity-100 !transition-opacity !duration-300"></div>
                
                {{-- Pagination --}}
                <div x-ref="pagination" class="swiper-pagination !bottom-3 !opacity-0 group-hover:!opacity-100 !transition-opacity !duration-300"></div>
            @endif
            
            {{-- Fallback containers if only 1 image (to prevent Swiper init errors if ever needed) --}}
            <div x-ref="prev" class="hidden"></div>
            <div x-ref="next" class="hidden"></div>
            <div x-ref="pagination" class="hidden"></div>
        </div>

        {{-- Overlay badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
            <span class="px-2.5 py-1 rounded-lg text-[11px] font-black uppercase tracking-wider flex items-center gap-1.5
                         {{ $car->condition === 'neuf' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-black' }}">
                @if($car->condition === 'neuf')
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg>
                @else
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                @endif
                {{ $car->condition === 'neuf' ? 'Neuf' : 'Occasion' }}
            </span>
        </div>

        <div class="absolute top-3 right-3 z-10">
            <span class="px-2.5 py-1 bg-amber-600 rounded-lg text-[11px] font-black">
                {{ $car->year }}
            </span>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-5">
        <div class="text-amber-500 text-xs font-bold uppercase tracking-widest mb-1">
            {{ $car->carModel->brand->name }}
        </div>
        <h3 class="text-lg font-black mb-1 leading-snug">
            {{ $car->carModel->name }}
        </h3>
        <div class="text-sm text-gray-500 mb-4">
            {{ $car->carModel->type ?? '' }}
        </div>

        {{-- Meta info row --}}
        <div class="flex flex-wrap gap-3 mb-4 pt-3 border-t border-white/5">
            <span class="meta-badge">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                {{ number_format($car->mileage) }} km
            </span>
            <span class="meta-badge">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022.547l-2.387 2.387a2 2 0 000 2.828l.586.586a2 2 0 002.828 0l2.387-2.387a2 2 0 00.547-1.022L21 16l-.572-.572a2 2 0 00-1.022.547z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21V5a2 2 0 012-2h2a2 2 0 012 2v4.672M12 13h4"></path>
                </svg>
                {{ $car->fuel_type }}
            </span>
            <span class="meta-badge">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $car->transmission }}
            </span>
        </div>

        {{-- Price + CTA --}}
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-500 uppercase tracking-widest">Prix</div>
                <div class="text-xl font-black text-white">{{ number_format($car->price) }} DH</div>
            </div>
            <a href="{{ route('cars.show', $car) }}"
               class="px-5 py-2.5 bg-amber-600 hover:bg-amber-500 text-white text-sm font-bold rounded-xl transition-all duration-200 active:scale-95">
                Voir Détails
            </a>
        </div>
    </div>
</div>
