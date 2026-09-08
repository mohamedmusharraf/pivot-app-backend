<?php

namespace App\Repositories;

use App\Models\GroupChallengeSession;
use App\Repositories\Contracts\GroupChallengeSessionRepositoryInterface;
use Illuminate\Support\Collection;

class GroupChallengeSessionRepository implements GroupChallengeSessionRepositoryInterface
{
    public function getForUser(int $userId): Collection
    {
        return GroupChallengeSession::query()
            ->with('challenge')
            ->where(function ($query) use ($userId) {
                $query->where('host_id', $userId)
                    ->orWhereHas('participants', function ($participantQuery) use ($userId) {
                        $participantQuery->where('user_id', $userId);
                    });
            })
            ->orderByDesc('started_at')
            ->orderByDesc('created_at')
            ->get();
    }

    public function getLeaderboardSessions(): Collection
    {
        return GroupChallengeSession::query()
            ->with('host:id,name')
            ->get(['host_id', 'started_at', 'ended_at']);
    }
}
