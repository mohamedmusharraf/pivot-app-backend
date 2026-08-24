<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupChallengeParticipant extends Model
{
    public const INVITE_STATUS_INVITED = 'invited';
    public const INVITE_STATUS_ACCEPTED = 'accepted';
    public const INVITE_STATUS_DECLINED = 'declined';

    protected $fillable = [
        'session_id',
        'user_id',
        'invite_status',
        'responded_at',
        'progress',
        'completed_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'progress' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(GroupChallengeSession::class, 'session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
