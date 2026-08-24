<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GroupChallengeParticipantLeft implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $sessionId,
        public int $userId,
        public string $userName,
    ) {
        Log::info('GroupChallengeParticipantLeft Event Triggered', [
            'session_id' => $this->sessionId,
            'user_id' => $this->userId,
        ]);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('session.' . $this->sessionId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'participant.left';
    }

    public function broadcastWith(): array
    {
        return [
            'session_id' => $this->sessionId,
            'user_id' => $this->userId,
            'user_name' => $this->userName,
        ];
    }
}
