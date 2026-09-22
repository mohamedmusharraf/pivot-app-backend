<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date_and_time',
        'location',
        'image',
        'is_active',
        'guests',
        'redirect_link',
    ];

    protected $casts = [
        'date_and_time' => 'datetime',
        'is_active' => 'boolean',
        'guests' => 'array',
    ];
}
