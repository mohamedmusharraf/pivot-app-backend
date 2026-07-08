<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ChallengeLog extends Model
{
     protected $table = 'challenge_logs';

    protected $fillable = [
        'user_id',
        'challenge_id',
        'status',
        'duration_minutes',
        'completed_at'
    ];
}
