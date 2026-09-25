<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Car;

class CarOption extends Model
{
    use HasFactory;

    protected $fillable = ['car_id', 'name', 'price'];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
