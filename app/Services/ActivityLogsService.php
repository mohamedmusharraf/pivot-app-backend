<?php

namespace App\Services;

use App\Repositories\Contracts\ActivityLogsRepositoryInterface;

class ActivityLogsService
{
    public function __construct(
        protected ActivityLogsRepositoryInterface $repository
    ) {}

    public function storeBatch(int $userId, array $events): bool
    {
        $now = now();
        $records = [];

        foreach ($events as $event) {
            $records[] = [
                'user_id' => $userId,
                'activity_id' => $event['activity_id'],
                'duration_minutes' => $event['duration_minutes'],
                'completed' => $event['completed'],
                'completed_at' => $event['completed_at'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        return $this->repository->insertBatch($records);
    }
}