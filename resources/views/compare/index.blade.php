<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-black mb-10">Vehicle Comparison</h1>

            @if($cars->isEmpty())
                <div class="glass p-20 text-center">
                    <h3 class="text-2xl font-bold mb-4">Comparison list is empty</h3>
                    <p class="text-gray-400 mb-8">Add up to 3 cars to see them side-by-side.</p>
                    <a href="{{ route('cars.index') }}" class="btn-premium">Browse Inventory</a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="p-6"></th>
                                @foreach($cars as $car)
                                    <th class="p-6 min-w-[300px]">
                                        <div class="glass p-4">
                                            <div class="aspect-video bg-gray-800 rounded-lg overflow-hidden mb-4">
                                                @if($car->images && count($car->images) > 0)
                                                    <img src="{{ asset('storage/' . $car->images[0]) }}" alt="{{ $car->carModel->name }}" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <h3 class="text-xl font-bold">{{ $car->carModel->brand->name }}</h3>
                                            <p class="text-amber-500 font-black">{{ $car->carModel->name }}</p>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr>
                                <td class="p-6 font-bold text-gray-400">Price</td>
                                @foreach($cars as $car)
                                    <td class="p-6 text-xl font-black">{{ number_format($car->price) }} DH</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-6 font-bold text-gray-400">Year</td>
                                @foreach($cars as $car)
                                    <td class="p-6 font-bold">{{ $car->year }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-6 font-bold text-gray-400">Mileage</td>
                                @foreach($cars as $car)
                                    <td class="p-6 font-bold">{{ number_format($car->mileage) }} km</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-6 font-bold text-gray-400">Fuel Type</td>
                                @foreach($cars as $car)
                                    <td class="p-6 font-bold">{{ $car->fuel_type }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-6 font-bold text-gray-400">Transmission</td>
                                @foreach($cars as $car)
                                    <td class="p-6 font-bold">{{ $car->transmission }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-6"></td>
                                @foreach($cars as $car)
                                    <td class="p-6">
                                        <a href="{{ route('cars.show', $car) }}" class="btn-premium py-2 block text-center text-sm">View Details</a>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
