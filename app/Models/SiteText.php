<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteText extends Model
{
    public $incrementing = false;

    protected $primaryKey = 'key';

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['key', 'value'];
}
