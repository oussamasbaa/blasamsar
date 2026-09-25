<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Car Inventory') }}
            </h2>
            <a href="{{ route('admin.cars.create') }}" class="btn-premium py-2 px-4 text-sm">Add Car</a>
        </div>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="glass overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white/5 text-gray-400 text-sm uppercase tracking-wider">
                            <th class="p-6">Car</th>
                            <th class="p-6">Price</th>
                            <th class="p-6">Condition</th>
                            <th class="p-6">Status</th>
                            <th class="p-6">Added</th>
                            <th class="p-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($cars as $car)
                            <tr class="hover:bg-white/3 transition-colors">
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-10 bg-gray-800 rounded overflow-hidden shrink-0">
                                            @if($car->images && count($car->images) > 0)
                                                <img src="{{ asset('storage/' . $car->images[0]) }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-white">{{ $car->carModel->brand->name }}</div>
                                            <div class="text-sm text-gray-400">{{ $car->carModel->name }} ({{ $car->year }})</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6 font-bold">
                                    {{ number_format($car->price) }} DH
                                </td>
                                <td class="p-6">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest {{ $car->condition === 'neuf' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                                        {{ $car->condition === 'neuf' ? 'Neuf' : 'Occasion' }}
                                    </span>
                                </td>
                                <td class="p-6">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest {{ $car->status === 'available' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                                        {{ $car->status }}
                                    </span>
                                </td>
                                <td class="p-6 text-sm text-gray-400">
                                    {{ $car->created_at->format('M d, Y') }}
                                </td>
                                <td class="p-6">
                                    <div class="flex gap-3">
                                        <a href="{{ route('admin.cars.edit', $car) }}" class="text-amber-500 hover:text-amber-400 text-sm font-bold">Edit</a>
                                        <form action="{{ route('admin.cars.destroy', $car) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-amber-500 hover:text-amber-400 text-sm font-bold">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-6 bg-white/3 border-t border-white/5">
                    {{ $cars->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
