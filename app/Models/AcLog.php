<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class AcLog extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'ac_logs';

    protected $fillable = [
        'device_id',
        'active_ac',
        'current_ampere',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'current_ampere' => 'float',
    ];
}
