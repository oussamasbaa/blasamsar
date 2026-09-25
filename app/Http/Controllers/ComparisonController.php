<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    public function index()
    {
        $comparison = \App\Models\Comparison::where('user_id', auth()->id())->first();
        $cars = $comparison ? \App\Models\Car::with('carModel.brand')->whereIn('id', $comparison->car_ids)->get() : collect();
        
        return view('compare.index', compact('cars'));
    }

    public function add(\App\Models\Car $car)
    {
        $comparison = \App\Models\Comparison::firstOrNew(['user_id' => auth()->id()]);
        
        $carIds = $comparison->car_ids ?? [];
        if (!in_array($car->id, $carIds)) {
            if (count($carIds) >= 3) {
                array_shift($carIds); // Limit to 3 cars
            }
            $carIds[] = $car->id;
        }
        
        $comparison->car_ids = $carIds;
        $comparison->save();

        return back()->with('success', 'Car added to comparison.');
    }
}
