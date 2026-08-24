<?php

use App\Models\GroupChallengeParticipant;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{userId}', function ($user, int $userId) {
    return (int) $user->id === $userId;
});

Broadcast::channel('session.{sessionId}', function ($user, int $sessionId) {
    return GroupChallengeParticipant::where('session_id', $sessionId)
        ->where('user_id', $user->id)
        ->exists();
});
