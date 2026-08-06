<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupChallengeStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param  array<int>  $recipientIds
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public int $userId,
        public array $recipientIds,
        public array $payload,
    ) {}

    public function broadcastOn(): array
    {
        return collect($this->recipientIds)
            ->filter()
            ->unique()
            ->map(fn (int $recipientId) => new PrivateChannel('user.' . $recipientId))
            ->values()
            ->all();
    }

    public function broadcastAs(): string
    {
        return 'group.challenge.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            ...$this->payload,
        ];
    }
}
