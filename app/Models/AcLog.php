<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'active_ac',
        'current_ampere',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];
}
