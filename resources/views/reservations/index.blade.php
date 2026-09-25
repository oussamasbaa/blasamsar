<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-black mb-10">My Reservations</h1>

            @if($reservations->isEmpty())
                <div class="glass p-20 text-center">
                    <h3 class="text-2xl font-bold mb-4">No active reservations</h3>
                    <p class="text-gray-400 mb-8">Ready to find your next ride? Explore our collection today.</p>
                    <a href="{{ route('cars.index') }}" class="btn-premium">Browse Inventory</a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($reservations as $reservation)
                        <div class="glass p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center">
                            <div class="w-full md:w-64 aspect-video bg-gray-800 rounded-xl overflow-hidden shrink-0">
                                @if($reservation->car->images && count($reservation->car->images) > 0)
                                    <img src="{{ asset('storage/' . $reservation->car->images[0]) }}" alt="{{ $reservation->car->carModel->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500 italic">No image</div>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-2xl font-bold">{{ $reservation->car->carModel->brand->name }} {{ $reservation->car->carModel->name }}</h3>
                                        <p class="text-gray-400">Reserved on {{ $reservation->reservation_date->format('M d, Y') }}</p>
                                    </div>
                                    <div class="px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest {{ $reservation->status === 'expired' ? 'bg-red-500/20 text-red-400' : 'bg-green-500/20 text-green-400' }}">
                                        {{ $reservation->status }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                                    <div>
                                        <span class="text-gray-500 text-sm block">Price</span>
                                        <span class="font-bold">{{ number_format($reservation->car->price) }} DH</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 text-sm block">Expires In</span>
                                        <span class="font-bold text-amber-400">{{ $reservation->expiry_date->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <div class="flex gap-4">
                                    <a href="{{ route('cars.show', $reservation->car) }}" class="text-sm font-semibold text-amber-500 hover:text-amber-400">View Car Details</a>
                                    <button class="text-sm font-semibold text-amber-500 hover:text-amber-400">Cancel Reservation</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
