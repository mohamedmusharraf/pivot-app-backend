<?php

namespace App\Services\AppLogs;

use App\Repositories\GoalLogsRepository;

class GoalLogsService
{
    public function __construct(
        protected GoalLogsRepository $goalLogsRepository
    ){}

    public function store(array $data)
    {
        return $this->goalLogsRepository->create($data);
    }
}