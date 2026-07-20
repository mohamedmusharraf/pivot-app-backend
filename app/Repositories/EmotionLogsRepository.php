<?php

namespace App\Repositories;

use App\Models\EmotionLogs;
use App\Repositories\Contracts\EmotionLogsRepositoryInterface;

class EmotionLogsRepository implements EmotionLogsRepositoryInterface
{
    public function create(array $data)
    {
        return EmotionLogs::create($data);
    }
}