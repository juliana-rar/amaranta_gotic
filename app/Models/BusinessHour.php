<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'day_order', 'is_open', 'shift1_start', 'shift1_end', 'shift2_start', 'shift2_end',
    ];

    protected $casts = ['day_order' => 'integer', 'is_open' => 'boolean'];
}
