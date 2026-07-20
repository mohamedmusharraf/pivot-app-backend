<?php

namespace App\Repositories;

use App\Models\FocusSessionLogs;
use App\Repositories\Contracts\FocusSessionLogsRepositoryInterface;

class FocusSessionLogsRepository implements FocusSessionLogsRepositoryInterface
{
    public function create(array $data): FocusSessionLogs
    {
        return FocusSessionLogs::create($data);
    }
}
