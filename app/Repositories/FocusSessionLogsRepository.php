<?php

namespace App\Repositories;

use App\Models\FocusSessionLogs;
use App\Repositories\Contracts\FocusSessionLogsRepositoryInterface;

class FocusSessionLogsRepository implements FocusSessionLogsRepositoryInterface
{
    public function insertBatch(array $records): bool
    {
        return FocusSessionLogs::insert($records);
    }
}