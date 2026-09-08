<?php

namespace App\Services;

use App\Models\ChallengeLog;
use App\Models\User;
use App\Repositories\Contracts\GroupChallengeSessionRepositoryInterface;
use Illuminate\Support\Collection;

class GroupChallengeSessionService
{
    public function __construct(
        protected GroupChallengeSessionRepositoryInterface $repository
    ) {}

    public function getLeaderboard(User $user): array
    {
        $groupSessions = $this->repository->getForUser($user->id)
            ->where('status', 'completed')
            ->filter(fn($session) => $session->started_at && $session->ended_at);

        $dailyChallengeCount = ChallengeLog::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $dailyDurationMinutes = ChallengeLog::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('duration_minutes');

        $groupDurationMinutes = $groupSessions->sum(
            fn($session) => $session->started_at->diffInMinutes($session->ended_at)
        );

        return [
            'host_id' => $user->id,
            'host_name' => $user->name,
            'total_challenge_count' => $dailyChallengeCount + $groupSessions->count(),
            'total_duration_minutes' => (int) $dailyDurationMinutes + $groupDurationMinutes,
        ];
    }
}
