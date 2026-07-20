<?php

namespace App\Services\AppLogs;

use App\Repositories\StreakLogsRepository;

class StreakLogsService
{
    public function __construct(
        protected StreakLogsRepository $streakLogsRepository
    ){}

    public function store(array $data)
    {
        return $this->streakLogsRepository->create($data);
    }
}