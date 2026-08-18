<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'label',
        'start_time',
        'end_time',
        'is_active',
    ];
}
