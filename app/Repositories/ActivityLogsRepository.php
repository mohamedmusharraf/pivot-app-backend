<?php

namespace App\Repositories;

use App\Models\ActivityLogs;
use App\Repositories\Contracts\ActivityLogsRepositoryInterface;

class ActivityLogsRepository implements ActivityLogsRepositoryInterface
{
    public function create(array $data)
    {
        return ActivityLogs::create($data);
    }
}
