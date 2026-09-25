<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMaintenance extends Model
{
    protected $fillable = [
        'vehicle_id', 'type', 'description', 'scheduled_date', 'completed_date',
        'cost', 'notes', 'insurance_expiration_date', 'technical_inspection_date'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'insurance_expiration_date' => 'date',
        'technical_inspection_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(FleetVehicle::class, 'vehicle_id');
    }
}
