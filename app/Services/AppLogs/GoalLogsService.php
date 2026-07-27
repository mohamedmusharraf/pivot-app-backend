<?php

namespace App\Services\AppLogs;

use App\Repositories\Contracts\GoalLogsRepositoryInterface;
use Illuminate\Support\Carbon;

class GoalLogsService
{
    public function __construct(
        protected GoalLogsRepositoryInterface $goalLogsRepository
    ) {}

    public function storeBatch(int $userId, array $events): bool
    {
        $now = now();
        $records = [];

        foreach ($events as $event) {
            $completedAt = Carbon::parse($event['completed_at']);
            
            $records[] = [
                'user_id' => $userId,
                'goal_id' => $event['goal_id'] ?? null,
                'target_minutes' => $event['target_minutes'],
                'achieved_minutes' => $event['achieved_minutes'],
                'completed' => $event['completed'] ?? ($event['achieved_minutes'] >= $event['target_minutes']),
                'goal_date' => $completedAt->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        return $this->goalLogsRepository->insertBatch($records);
    }
}