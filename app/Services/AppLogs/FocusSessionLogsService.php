<?php

namespace App\Services\AppLogs;

use App\Repositories\Contracts\FocusSessionLogsRepositoryInterface;
use Illuminate\Support\Carbon;

class FocusSessionLogsService
{
    public function __construct(
        protected FocusSessionLogsRepositoryInterface $repository
    ) {}

    public function store(int $userId, array $events): bool
    {
        $now = now();
        $records = [];

        foreach ($events as $event) {
            $records[] = [
                'user_id' => $userId,
                'started_at' => Carbon::parse($event['started_at'])->toDateTimeString(),
                'ended_at' => isset($event['ended_at']) ? Carbon::parse($event['ended_at'])->toDateTimeString() : null,
                'duration_minutes' => $event['duration_minutes'] ?? 0,
                'completed' => $event['completed'] ?? false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        return $this->repository->insertBatch($records);
    }
}