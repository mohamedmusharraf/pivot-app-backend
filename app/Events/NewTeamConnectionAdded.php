<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewTeamConnectionAdded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public array $connection
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->connection['inviter']['id']),
            new PrivateChannel('user.' . $this->connection['connected_user']['id']),
        ];
    }

    public function broadcastAs(): string
    {
        return 'team.connection.added';
    }

    public function broadcastWith(): array
    {
        return $this->connection;
    }
}
