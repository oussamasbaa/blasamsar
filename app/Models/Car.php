<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_model_id', 'year', 'price', 'mileage', 
        'fuel_type', 'transmission', 'color', 'description', 
        'images', 'status', 'condition'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function options()
    {
        return $this->hasMany(CarOption::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
