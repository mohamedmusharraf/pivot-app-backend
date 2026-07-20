<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FocusSessionLogs extends Model
{
    protected $fillable = [
        'user_id',
        'started_at',
        'ended_at',
        'duration_minutes',
        'completed',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_minutes' => 'integer',
        'completed' => 'boolean',
    ];
}
