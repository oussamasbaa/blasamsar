<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FleetVehicle extends Model
{
    protected $fillable = [
        'brand', 'model', 'registration_number', 'year', 'status', 'assigned_driver_id', 'image'
    ];

    public function assignedDriver()
    {
        return $this->belongsTo(Worker::class, 'assigned_driver_id');
    }

    public function assignments()
    {
        return $this->hasMany(VehicleAssignment::class, 'vehicle_id');
    }

    public function maintenances()
    {
        return $this->hasMany(VehicleMaintenance::class, 'vehicle_id');
    }
}
