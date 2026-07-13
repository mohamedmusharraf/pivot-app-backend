<?php

namespace App\Services;

use App\Repositories\Contracts\ActivityLogsRepositoryInterface;

class ActivityLogsService
{
    public function __construct(
        protected ActivityLogsRepositoryInterface $repository
    ) {}

    public function store(array $data)
    {
        return $this->repository->create($data);
    }
}
