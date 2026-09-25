<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = \App\Models\Car::with('carModel.brand')->latest()->paginate(10);
        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        $brands = \App\Models\Brand::all();
        $models = \App\Models\CarModel::all();
        return view('admin.cars.create', compact('brands', 'models'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'year'         => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price'        => 'required|numeric|min:0',
            'mileage'      => 'required|integer|min:0',
            'fuel_type'    => 'required|string',
            'transmission' => 'required|string',
            'description'  => 'nullable|string',
            'status'       => 'required|string',
            'condition'    => 'required|in:neuf,occasion',
            'images.*'     => 'nullable|image|max:2048',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('cars', 'public');
                $imagePaths[] = $path;
            }
        }

        $validated['images'] = $imagePaths;

        \App\Models\Car::create($validated);

        return redirect()->route('admin.cars.index')->with('success', 'Car added successfully.');
    }

    public function edit(\App\Models\Car $car)
    {
        $brands = \App\Models\Brand::all();
        $models = \App\Models\CarModel::all();
        return view('admin.cars.edit', compact('car', 'brands', 'models'));
    }

    public function update(Request $request, \App\Models\Car $car)
    {
        $validated = $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'year'         => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price'        => 'required|numeric|min:0',
            'mileage'      => 'required|integer|min:0',
            'fuel_type'    => 'required|string',
            'transmission' => 'required|string',
            'description'  => 'nullable|string',
            'status'       => 'required|string',
            'condition'    => 'required|in:neuf,occasion',
            'images.*'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('images')) {
            $imagePaths = $car->images ?? [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('cars', 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        $car->update($validated);

        return redirect()->route('admin.cars.index')->with('success', 'Car updated successfully.');
    }

    public function destroy(\App\Models\Car $car)
    {
        $car->delete();
        return redirect()->route('admin.cars.index')->with('success', 'Car deleted successfully.');
    }
}
