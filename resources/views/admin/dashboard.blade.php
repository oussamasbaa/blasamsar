<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                <div class="glass p-8 border-l-4 border-amber-500">
                    <div class="text-gray-400 text-sm font-medium uppercase mb-1">Total Cars</div>
                    <div class="text-4xl font-black">{{ $stats['total_cars'] }}</div>
                </div>
                <div class="glass p-8 border-l-4 border-green-500">
                    <div class="text-gray-400 text-sm font-medium uppercase mb-1">Total Reservations</div>
                    <div class="text-4xl font-black">{{ $stats['total_reservations'] }}</div>
                </div>
                <div class="glass p-8 border-l-4 border-purple-500">
                    <div class="text-gray-400 text-sm font-medium uppercase mb-1">Active Users</div>
                    <div class="text-4xl font-black">{{ $stats['total_users'] }}</div>
                </div>
                <div class="glass p-8 border-l-4 border-yellow-500">
                    <div class="text-gray-400 text-sm font-medium uppercase mb-1">Pending</div>
                    <div class="text-4xl font-black">{{ $stats['pending_reservations'] }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Quick Actions --}}
                <div class="lg:col-span-1">
                    <div class="glass p-8">
                        <h3 class="text-xl font-bold mb-6">Quick Actions</h3>
                        <div class="space-y-4">
                            <a href="{{ route('admin.cars.create') }}" class="btn-premium w-full block text-center">Add New Car</a>
                            <a href="{{ route('admin.cars.index') }}" class="w-full block text-center py-3 bg-white/5 hover:bg-white/10 rounded-xl font-bold transition-all border border-white/10">Manage Inventory</a>
                            <a href="#" class="w-full block text-center py-3 bg-white/5 hover:bg-white/10 rounded-xl font-bold transition-all border border-white/10">User Management</a>
                        </div>
                    </div>
                </div>

                {{-- Recent Reservations --}}
                <div class="lg:col-span-2">
                    <div class="glass p-8 h-full">
                        <h3 class="text-xl font-bold mb-6">Recent Reservations</h3>
                        @if($recent_reservations->isEmpty())
                            <p class="text-gray-500 italic">No recent reservations found.</p>
                        @else
                            <div class="space-y-4">
                                @foreach($recent_reservations as $res)
                                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl border border-white/10">
                                        <div>
                                            <div class="font-bold">{{ $res->user->name }}</div>
                                            <div class="text-sm text-gray-400">{{ $res->car->carModel->brand->name }} {{ $res->car->carModel->name }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-bold text-amber-400">{{ $res->reservation_date->diffForHumans() }}</div>
                                            <div class="text-xs uppercase tracking-widest font-black {{ $res->status === 'pending' ? 'text-yellow-500' : 'text-green-500' }}">
                                                {{ $res->status }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
