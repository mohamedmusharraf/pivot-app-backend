<?php

namespace App\Repositories\Contracts;

interface GoalLogsRepositoryInterface
{
    public function create(array $data);

    public function insertBatch(array $records): bool;
}