<?php

namespace App\Events;

use App\DTO\TeamConnectionDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewTeamConnectionAdded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public TeamConnectionDTO $dto)
    {
        Log::info('NewTeamConnectionAdded Event Triggered', [
            'dto' => $dto->toArray()
        ]);
    }


    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->dto->inviterId),
            new PrivateChannel('user.' . $this->dto->connectedUserId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'team.connection.added';
    }

    public function broadcastWith(): array
    {
        Log::info('BroadcastWith Executed');
        return $this->dto->toArray();
    }
}
