<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Device extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'devices';

    protected $fillable = [
        'device_id',
        'name',
        'location',
        'ip_address',
        'hardware_type',
        'status',
        'auth_token',
        'num_ac',
        'description',
    ];

    protected $casts = [
        'num_ac' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
