<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ChallengeInviteReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public int $recipientId,
        public array $payload,
    ) {
        Log::info('ChallengeInviteReceived Event Triggered', [
            'recipient_id' => $this->recipientId,
            'payload' => $this->payload,
        ]);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->recipientId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'challenge.invite';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
