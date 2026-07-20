<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StreakLogs extends Model
{
    protected $fillable = [
        'user_id',
        'current_streak',
        'longest_streak',
        'last_completed_date'
    ];
}
