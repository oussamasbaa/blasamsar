<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    protected $fillable = [
        'company_id', 'branch_id', 'name', 'position', 'department', 
        'phone', 'email', 'profile_picture', 'qr_token', 'status', 
        'shift_id', 'leave_balance_vacation', 'leave_balance_sick'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->qr_token)) {
                $model->qr_token = \Illuminate\Support\Str::uuid()->toString();
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function pointages()
    {
        return $this->hasMany(Pointage::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function vehicleAssignments()
    {
        return $this->hasMany(VehicleAssignment::class, 'driver_id');
    }
}
