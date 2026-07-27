<?php

namespace App\Repositories\Contracts;

interface FocusSessionLogsRepositoryInterface
{
    public function insertBatch(array $records): bool;
}