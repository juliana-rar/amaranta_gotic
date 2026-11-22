<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    public $timestamps = false;

    protected $fillable = ['author_name', 'quote', 'display_order'];

    protected $casts = ['display_order' => 'integer'];
}
