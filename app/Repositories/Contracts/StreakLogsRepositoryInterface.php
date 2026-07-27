<?php

namespace App\Repositories\Contracts;

interface StreakLogsRepositoryInterface
{
    public function updateOrCreateStreak(int $userId, array $data);
}