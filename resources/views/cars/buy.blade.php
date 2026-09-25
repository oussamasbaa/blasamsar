<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            {{ __('Checkout: ') }} <span class="text-amber-500">{{ $car->carModel->brand->name }} {{ $car->carModel->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="glass p-8 md:p-12">
                <div class="flex items-center gap-6 mb-10 pb-8 border-b border-white/10">
                    <div class="w-32 h-24 rounded-xl overflow-hidden shrink-0">
                        @if($car->images && count($car->images) > 0)
                            <img src="{{ asset('storage/' . $car->images[0]) }}" alt="Car" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/car-placeholder.png') }}" alt="Car" class="w-full h-full object-cover opacity-60 bg-white/5">
                        @endif
                    </div>
                    <div>
                        <h3 class="text-3xl font-black">{{ $car->carModel->brand->name }} {{ $car->carModel->name }}</h3>
                        <div class="text-2xl text-amber-500 font-bold mt-2">{{ number_format($car->price) }} DH</div>
                    </div>
                </div>

                <form action="{{ route('reservations.store', $car) }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Nom Complet</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-amber-500 focus:border-amber-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Email</label>
                            <input type="email" name="email" value="{{ auth()->user()->email }}" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-amber-500 focus:border-amber-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Carte Nationale (CIN)</label>
                            <input type="text" name="cin" placeholder="Ex: AB123456" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-amber-500 focus:border-amber-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Numéro de Téléphone</label>
                            <input type="tel" name="phone" placeholder="+212 600 000 000" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-amber-500 focus:border-amber-500 transition-all">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-white/10">
                        <h4 class="text-xl font-bold mb-6 flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Informations Bancaires
                        </h4>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Numéro de Compte (RIB / IBAN)</label>
                                <input type="text" name="bank_account" placeholder="MA00 0000 0000 0000 0000 0000 0000" required
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-amber-500 focus:border-amber-500 transition-all font-mono">
                            </div>
                            <div class="grid grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Banque</label>
                                    <input type="text" name="bank_name" placeholder="Ex: Attijariwafa Bank" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-amber-500 focus:border-amber-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">SWIFT / BIC</label>
                                    <input type="text" name="swift" placeholder="Optional"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-amber-500 focus:border-amber-500 transition-all font-mono uppercase">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 flex flex-col sm:flex-row gap-4 items-center justify-between">
                        <p class="text-sm text-gray-500">
                            By clicking confirm, you agree to our <a href="#" class="text-amber-500 hover:underline">Terms of Service</a>.
                        </p>
                        <button type="submit" class="btn-premium px-10 py-4 text-lg w-full sm:w-auto flex items-center justify-center gap-2">
                            Confirm Purchase
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
