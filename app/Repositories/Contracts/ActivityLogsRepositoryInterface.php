<?php

namespace App\Repositories\Contracts;

interface ActivityLogsRepositoryInterface
{
    public function insertBatch(array $records): bool;
}