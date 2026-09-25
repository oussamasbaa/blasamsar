<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['company_id', 'name', 'address', 'latitude', 'longitude', 'radius_meters'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }
}
