<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'logo', 'address'];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }
}
