<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class DeviceRequest extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'device_requests';

    protected $fillable = [
        'user_nip',
        'operator_name',
        'room_name',
        'location',
        'num_ac',
        'description',
        'status', // 'pending', 'approved', 'rejected'
    ];

    protected $casts = [
        'num_ac' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
