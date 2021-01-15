<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $guarded = ['*'];

    protected $casts = ['meta' => 'json'];
}
