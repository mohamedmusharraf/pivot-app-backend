<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'challenge_id');
    }
}
