<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GroupChallengeCompleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public int $sessionId,
        public array $payload,
    ) {
        Log::info('GroupChallengeCompleted Event Triggered', [
            'session_id' => $this->sessionId,
            'payload' => $this->payload,
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
        return 'challenge.completed';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
