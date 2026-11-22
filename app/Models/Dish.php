<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title', 'description', 'image_url', 'category', 'price_text', 'show_on_home', 'display_order',
    ];

    protected $casts = [
        'show_on_home' => 'boolean',
        'display_order' => 'integer',
    ];
}
