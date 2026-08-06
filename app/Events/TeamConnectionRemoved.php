<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TeamConnectionRemoved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $inviterId,
        public int $connectedUserId,
        public array $payload,
    ) {
        Log::info('TeamConnectionRemoved Event Triggered', [
            'inviter_id' => $this->inviterId,
            'connected_user_id' => $this->connectedUserId,
            'payload' => $this->payload,
        ]);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->inviterId),
            new PrivateChannel('user.' . $this->connectedUserId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'team.connection.removed';
    }

    public function broadcastWith(): array
    {
        Log::info('TeamConnectionRemoved BroadcastWith Executed');

        return $this->payload;
    }
}
