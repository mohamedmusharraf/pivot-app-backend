<?php

namespace App\Services\AppLogs;

use App\Repositories\Contracts\FocusSessionLogsRepositoryInterface;

class FocusSessionLogsService
{
    protected $repository;

    public function __construct(FocusSessionLogsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function store(array $data)
    {
        return $this->repository->create($data);
    }
}
