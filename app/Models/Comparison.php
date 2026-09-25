<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comparison extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'car_ids'];

    protected $casts = [
        'car_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
