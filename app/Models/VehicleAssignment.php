<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleAssignment extends Model
{
    protected $fillable = [
        'vehicle_id', 'driver_id', 'assigned_at', 'returned_at', 'purpose', 'start_mileage', 'end_mileage'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(FleetVehicle::class, 'vehicle_id');
    }

    public function driver()
    {
        return $this->belongsTo(Worker::class, 'driver_id');
    }
}
