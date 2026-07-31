<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
