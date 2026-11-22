<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'customer_name', 'phone', 'date', 'time', 'party_size',
        'children_count', 'high_chair_count', 'comments', 'status',
    ];

    protected $casts = [
        'date' => 'date',
        'party_size' => 'integer',
        'children_count' => 'integer',
        'high_chair_count' => 'integer',
    ];
}
