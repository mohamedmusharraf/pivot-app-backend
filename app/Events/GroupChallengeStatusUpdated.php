<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GroupChallengeStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param array<int> $recipientIds
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public int $userId,
        public array $recipientIds,
        public array $payload,
    ) {
        Log::info('GroupChallengeStatusUpdated Event Triggered', [
            'user_id' => $this->userId,
            'recipient_ids' => $this->recipientIds,
            'payload' => $this->payload,
        ]);
    }

    public function broadcastOn(): array
    {
        $channels = [];

        foreach ($this->recipientIds as $recipientId) {
            if (! empty($recipientId)) {
                $channels[] = new PrivateChannel('user.' . $recipientId);
            }
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'group.challenge.status.updated';
    }

    public function broadcastWith(): array
    {
        Log::info('GroupChallengeStatusUpdated BroadcastWith Executed');

        return [
            'user_id' => $this->userId,
            ...$this->payload,
        ];
    }
}