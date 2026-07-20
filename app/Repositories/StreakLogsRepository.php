<?php

namespace App\Repositories;

use App\Models\StreakLogs;
use App\Repositories\Contracts\StreakLogsRepositoryInterface;

class StreakLogsRepository implements StreakLogsRepositoryInterface
{
    public function create(array $data)
    {
        return StreakLogs::create($data);
    }
}