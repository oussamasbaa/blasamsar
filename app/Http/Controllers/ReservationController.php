<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = auth()->user()->reservations()->with('car.carModel.brand')->latest()->get();
        return view('reservations.index', compact('reservations'));
    }

    public function store(Request $request, \App\Models\Car $car)
    {
        if ($car->status !== 'available') {
            return back()->with('error', 'This car is no longer available for reservation.');
        }

        $buyerInfo = $request->only(['name', 'email', 'cin', 'phone', 'bank_account', 'bank_name', 'swift']);

        $reservation = auth()->user()->reservations()->create([
            'car_id' => $car->id,
            'reservation_date' => now(),
            'expiry_date' => now()->addDays(3),
            'status' => 'pending',
            'buyer_info' => $buyerInfo,
        ]);

        $car->update(['status' => 'reserved']);

        return redirect()->route('reservations.facture', $reservation)->with('success', 'Purchase confirmed successfully.');
    }

    public function facture(\App\Models\Reservation $reservation)
    {
        // Ensure user is authorized to view this facture
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        $reservation->load(['car.carModel.brand', 'car.options']);
        return view('reservations.facture', compact('reservation'));
    }
}
