<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boost extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'cooldown_minutes',
        'next_available_at',
    ];

    protected $casts = [
        'next_available_at' => 'datetime',
    ];
}
