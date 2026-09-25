<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Add New Car') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="glass p-8">
                <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Car Model</label>
                            <select name="car_model_id" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500">
                                @foreach($models as $model)
                                    <option value="{{ $model->id }}">{{ $model->brand->name }} {{ $model->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Year</label>
                            <input type="number" name="year" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Price (DH)</label>
                            <input type="number" name="price" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Mileage (km)</label>
                            <input type="number" name="mileage" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Fuel Type</label>
                            <input type="text" name="fuel_type" required placeholder="e.g. Gasoline, Hybrid" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Transmission</label>
                            <select name="transmission" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                                <option value="Automatic">Automatic</option>
                                <option value="Manual">Manual</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Description</label>
                        <textarea name="description" rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3"></textarea>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                        <select name="status" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                            <option value="available">Available</option>
                            <option value="reserved">Reserved</option>
                            <option value="sold">Sold</option>
                        </select>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Condition</label>
                        <select name="condition" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                            <option value="neuf">🆕 Neuf</option>
                            <option value="occasion">🔄 Occasion</option>
                        </select>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Car Images</label>
                        <input type="file" name="images[]" multiple class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500 cursor-pointer">
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="btn-premium flex-1">Save Car</button>
                        <a href="{{ route('admin.cars.index') }}" class="px-8 py-3 bg-white/5 hover:bg-white/10 rounded-xl font-bold border border-white/10 text-center">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
