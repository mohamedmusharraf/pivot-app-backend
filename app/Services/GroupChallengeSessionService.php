<?php

namespace App\Services;

use App\Repositories\Contracts\GroupChallengeSessionRepositoryInterface;
use Illuminate\Support\Collection;

class GroupChallengeSessionService
{
    public function __construct(
        protected GroupChallengeSessionRepositoryInterface $repository
    ) {}

    public function getForUser(int $userId): Collection
    {
        return $this->repository->getForUser($userId)->map(function ($session) {
            return [
                'challenge_name' => $session->challenge?->activity_title,
                'host_id' => $session->host_id,
                'status' => $session->status,
                'started_at' => $session->started_at,
                'ended_at' => $session->ended_at,
                'play_time_minutes' => $session->started_at && $session->ended_at
                    ? $session->started_at->diffInMinutes($session->ended_at)
                    : null,
            ];
        });
    }
}
