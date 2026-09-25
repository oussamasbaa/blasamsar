<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_cars' => \App\Models\Car::count(),
            'total_reservations' => \App\Models\Reservation::count(),
            'total_users' => \App\Models\User::count(),
            'pending_reservations' => \App\Models\Reservation::where('status', 'pending')->count(),
        ];

        $recent_reservations = \App\Models\Reservation::with(['user', 'car.carModel.brand'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_reservations'));
    }
}
