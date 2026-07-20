<?php

namespace App\Services\AppLogs;

use App\Repositories\EmotionLogsRepository;

class EmotionLogsService
{
    public function __construct(
        protected EmotionLogsRepository $emotionLogsRepository
    ){}

    public function store(array $data)
    {
        return $this->emotionLogsRepository->create($data);
    }
}