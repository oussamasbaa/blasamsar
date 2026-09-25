<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'worker_id', 'date', 'check_in_at', 'check_out_at', 'status',
        'total_hours', 'latitude_in', 'longitude_in', 'latitude_out', 'longitude_out',
        'selfie_in', 'selfie_out', 'ip_in', 'ip_out', 'device_in', 'device_out'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'total_hours' => 'decimal:2',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
