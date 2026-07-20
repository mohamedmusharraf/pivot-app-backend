<?php

namespace App\Repositories\Contracts;

use App\Models\FocusSessionLogs;

interface FocusSessionLogsRepositoryInterface
{
    public function create(array $data): FocusSessionLogs;
}
