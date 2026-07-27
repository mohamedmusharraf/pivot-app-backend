<?php

namespace App\Services\AppLogs;

use App\Repositories\Contracts\StreakLogsRepositoryInterface;

class StreakLogsService
{
    public function __construct(
        protected StreakLogsRepositoryInterface $streakLogsRepository
    ) {}

    public function store(int $userId, array $data)
    {
        return $this->streakLogsRepository->updateOrCreateStreak($userId, $data);
    }
}