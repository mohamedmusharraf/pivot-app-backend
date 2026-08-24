<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GroupChallengePaused implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $sessionId,
    ) {
        Log::info('GroupChallengePaused Event Triggered', [
            'session_id' => $this->sessionId,
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
        return 'challenge.paused';
    }

    public function broadcastWith(): array
    {
        return [
            'session_id' => $this->sessionId,
        ];
    }
}
