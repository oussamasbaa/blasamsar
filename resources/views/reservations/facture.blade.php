<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 mt-10">
        <div class="max-w-4xl mx-auto">
            
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('reservations.index') }}" class="text-gray-400 hover:text-white transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Retour aux réservations
                </a>
                <button onclick="window.print()" class="btn-outline flex items-center gap-2 py-2 px-4 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Imprimer la Facture
                </button>
            </div>

            <!-- Facture Container (Styled for print) -->
            <div class="glass p-10 md:p-16 relative overflow-hidden print:bg-white print:text-black print:shadow-none print:border-none" id="facture-content">
                
                <!-- Watermark -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-5 pointer-events-none text-9xl font-black rotate-[-45deg] print:opacity-[0.03]">
                    APEX CAR
                </div>

                <!-- Header -->
                <div class="flex justify-between items-start mb-16 border-b border-white/10 print:border-gray-300 pb-8">
                    <div>
                        <div class="text-3xl font-black mb-2 tracking-tighter">
                            APEX<span class="text-amber-600"> CAR</span>
                        </div>
                        <p class="text-gray-400 print:text-gray-600 text-sm">
                            Oujda, Maroc<br>
                            +212 600 000 000<br>
                            contact@apexcar.ma
                        </p>
                    </div>
                    <div class="text-right">
                        <h1 class="text-4xl font-bold uppercase tracking-widest text-amber-500 mb-2">Facture</h1>
                        <p class="text-gray-400 print:text-gray-600 text-sm font-mono">
                            N° F-{{ date('Y') }}-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}<br>
                            Date: {{ $reservation->created_at->format('d/m/Y') }}
                        </p>
                        <div class="mt-4 inline-block px-4 py-1 rounded-full text-xs font-bold uppercase {{ $reservation->status === 'confirmed' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' }}">
                            {{ $reservation->status === 'pending' ? 'En attente de paiement' : 'Payé' }}
                        </div>
                    </div>
                </div>

                <!-- Client Info -->
                <div class="grid grid-cols-2 gap-12 mb-16">
                    <div>
                        <h3 class="text-xs font-bold text-gray-500 print:text-gray-400 uppercase tracking-widest mb-4">Informations Client</h3>
                        <div class="space-y-1">
                            <p class="font-bold text-xl">{{ $reservation->buyer_info['name'] ?? $reservation->user->name }}</p>
                            <p class="text-gray-400 print:text-gray-700">CIN: <span class="font-mono text-white print:text-black">{{ $reservation->buyer_info['cin'] ?? 'N/A' }}</span></p>
                            <p class="text-gray-400 print:text-gray-700">Email: {{ $reservation->buyer_info['email'] ?? $reservation->user->email }}</p>
                            <p class="text-gray-400 print:text-gray-700">Téléphone: {{ $reservation->buyer_info['phone'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-gray-500 print:text-gray-400 uppercase tracking-widest mb-4">Informations Bancaires Soumises</h3>
                        <div class="space-y-1 text-right">
                            <p class="font-bold">{{ $reservation->buyer_info['bank_name'] ?? 'N/A' }}</p>
                            <p class="text-gray-400 print:text-gray-700 text-sm font-mono">{{ $reservation->buyer_info['bank_account'] ?? 'N/A' }}</p>
                            @if(!empty($reservation->buyer_info['swift']))
                                <p class="text-gray-400 print:text-gray-700 text-sm">SWIFT: {{ $reservation->buyer_info['swift'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <table class="w-full mb-16 text-left">
                    <thead>
                        <tr class="border-b border-white/20 print:border-gray-300">
                            <th class="py-4 text-xs font-bold text-gray-500 print:text-gray-400 uppercase tracking-widest">Description du Véhicule</th>
                            <th class="py-4 text-xs font-bold text-gray-500 print:text-gray-400 uppercase tracking-widest text-center">Année</th>
                            <th class="py-4 text-xs font-bold text-gray-500 print:text-gray-400 uppercase tracking-widest text-right">Prix Unitaire</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 print:divide-gray-200">
                        <tr>
                            <td class="py-6">
                                <div class="font-bold text-lg">{{ $reservation->car->carModel->brand->name }} {{ $reservation->car->carModel->name }}</div>
                                <div class="text-sm text-gray-400 print:text-gray-600 mt-1">
                                    {{ $reservation->car->fuel_type }} &bull; {{ $reservation->car->transmission }} &bull; {{ $reservation->car->condition }}
                                </div>
                                <div class="text-sm text-gray-400 print:text-gray-600">
                                    Kilométrage: {{ number_format($reservation->car->mileage) }} km
                                </div>
                            </td>
                            <td class="py-6 text-center text-gray-300 print:text-gray-700">
                                {{ $reservation->car->year }}
                            </td>
                            <td class="py-6 text-right font-mono font-bold text-lg">
                                {{ number_format($reservation->car->price, 2) }} DH
                            </td>
                        </tr>
                        @if($reservation->car->options && count($reservation->car->options) > 0)
                            @foreach($reservation->car->options as $option)
                                <tr>
                                    <td class="py-4 pl-4 border-l-2 border-amber-500/50">
                                        <div class="text-sm">Option: {{ $option->name }}</div>
                                    </td>
                                    <td class="py-4 text-center text-gray-300 print:text-gray-700">-</td>
                                    <td class="py-4 text-right font-mono text-sm text-gray-400 print:text-gray-600">
                                        +{{ number_format($option->price, 2) }} DH
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <!-- Totals -->
                <div class="flex justify-end border-t border-white/20 print:border-gray-300 pt-8">
                    <div class="w-1/2 space-y-4">
                        @php
                            $subtotal = $reservation->car->price;
                            $optionsTotal = $reservation->car->options ? $reservation->car->options->sum('price') : 0;
                            $tax = ($subtotal + $optionsTotal) * 0.20; // 20% TVA example
                            $total = $subtotal + $optionsTotal + $tax;
                        @endphp
                        
                        <div class="flex justify-between text-gray-400 print:text-gray-600">
                            <span>Sous-total HT</span>
                            <span class="font-mono">{{ number_format($subtotal + $optionsTotal, 2) }} DH</span>
                        </div>
                        <div class="flex justify-between text-gray-400 print:text-gray-600">
                            <span>TVA (20%)</span>
                            <span class="font-mono">{{ number_format($tax, 2) }} DH</span>
                        </div>
                        <div class="flex justify-between text-2xl font-black text-white print:text-black border-t border-white/10 print:border-gray-200 pt-4 mt-2">
                            <span>Total TTC</span>
                            <span class="text-amber-500">{{ number_format($total, 2) }} DH</span>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-20 text-center text-sm text-gray-500 print:text-gray-400 border-t border-white/5 print:border-gray-200 pt-8">
                    <p>Merci pour votre confiance.</p>
                    <p class="text-xs mt-2">Cette facture est un reçu électronique généré suite à votre achat de réservation.</p>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        @media print {
            body { background: white !important; color: black !important; }
            nav, footer, .btn-outline, a { display: none !important; }
            .glass { border: none !important; box-shadow: none !important; background: transparent !important; }
            .text-white { color: black !important; }
            .border-white\/10, .border-white\/20, .border-white\/5 { border-color: #e5e7eb !important; }
        }
    </style>
</x-app-layout>
