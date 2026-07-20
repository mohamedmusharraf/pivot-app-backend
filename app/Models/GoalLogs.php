<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalLogs extends Model
{
    protected $fillable =[
        'user_id',
        'goal_id',
        'target_minutes',
        'achieved_minutes',
        'completed',
        'goal_date'
    ];
}
