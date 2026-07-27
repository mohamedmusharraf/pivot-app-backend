<?php

namespace App\Repositories;

use App\Models\StreakLogs;
use App\Repositories\Contracts\StreakLogsRepositoryInterface;

class StreakLogsRepository implements StreakLogsRepositoryInterface
{
    public function updateOrCreateStreak(int $userId, array $data)
    {
        $existing = StreakLogs::where('user_id', $userId)->first();

        $currentStreak = $data['current_streak'];
        $longestStreak = $existing 
            ? max($existing->longest_streak, $currentStreak) 
            : $currentStreak;

        return StreakLogs::updateOrCreate(
            ['user_id' => $userId],
            [
                'current_streak'      => $currentStreak,
                'longest_streak'      => $longestStreak,
                'last_completed_date' => $data['last_completed_date'],
            ]
        );
    }
}