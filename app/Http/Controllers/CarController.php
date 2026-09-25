<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Car;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with('carModel.brand')->where('status', 'available');

        if ($request->filled('brand')) {
            $query->whereHas('carModel.brand', function($q) use ($request) {
                $q->where('id', $request->brand);
            });
        }

        if ($request->filled('model')) {
            $query->where('car_model_id', $request->model);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        $cars = $query->latest()->paginate(12);
        $brands = Brand::all();
        
        $availableColors = [];
        if ($request->filled('brand')) {
            $availableColors = Car::whereHas('carModel.brand', function($q) use ($request) {
                $q->where('id', $request->brand);
            })->whereNotNull('color')->distinct()->pluck('color');
        }

        return view('cars.index', compact('cars', 'brands', 'availableColors'));
    }

    public function show(Car $car)
    {
        $car->load(['carModel.brand', 'options']);
        return view('cars.show', compact('car'));
    }

    public function buy(Car $car)
    {
        if ($car->status !== 'available') {
            return redirect()->route('cars.show', $car)->with('error', 'This car is no longer available.');
        }
        
        $car->load(['carModel.brand']);
        return view('cars.buy', compact('car'));
    }

    public function fiche(Car $car)
    {
        $car->load(['carModel.brand', 'options']);
        return view('cars.fiche', compact('car'));
    }

    public function configurator()
    {
        $brands = Brand::withCount(['carModels' => function ($query) {
            $query->whereHas('cars', fn ($q) => $q->where('status', 'available'));
        }])->has('carModels.cars')->get();

        $fuelTypes = Car::where('status', 'available')
            ->whereNotNull('fuel_type')
            ->distinct()
            ->pluck('fuel_type')
            ->sort()
            ->values();

        $transmissions = Car::where('status', 'available')
            ->whereNotNull('transmission')
            ->distinct()
            ->pluck('transmission')
            ->sort()
            ->values();

        $conditions = Car::where('status', 'available')
            ->whereNotNull('condition')
            ->distinct()
            ->pluck('condition')
            ->sort()
            ->values();

        $allColors = Car::where('status', 'available')
            ->whereNotNull('color')
            ->distinct()
            ->pluck('color')
            ->sort()
            ->values();

        $priceRange = Car::where('status', 'available')
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return view('configurator', compact(
            'brands', 'fuelTypes', 'transmissions', 'conditions', 'allColors', 'priceRange'
        ));
    }

    public function getModels(Request $request)
    {
        $brandId = $request->input('brand_id');

        if (!$brandId) {
            return response()->json([]);
        }

        $models = CarModel::where('brand_id', $brandId)
            ->whereHas('cars', fn ($q) => $q->where('status', 'available'))
            ->withCount(['cars' => fn ($q) => $q->where('status', 'available')])
            ->get();

        return response()->json($models);
    }

    public function getColors(Request $request)
    {
        $query = Car::where('status', 'available')->whereNotNull('color');

        if ($request->filled('brand_id')) {
            $query->whereHas('carModel.brand', function ($q) use ($request) {
                $q->where('id', $request->brand_id);
            });
        }

        if ($request->filled('model_id')) {
            $query->where('car_model_id', $request->model_id);
        }

        $colors = $query->distinct()->pluck('color')->sort()->values();

        return response()->json($colors);
    }
}
