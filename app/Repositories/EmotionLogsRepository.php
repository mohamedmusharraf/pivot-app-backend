<?php

namespace App\Repositories;

use App\Models\EmotionLogs;
use App\Repositories\Contracts\EmotionLogsRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EmotionLogsRepository implements EmotionLogsRepositoryInterface
{
    public function create(array $data)
    {
        return EmotionLogs::create($data);
    }

    public function insertBatch(array $records): bool
    {
        return DB::transaction(function () use ($records) {
            return EmotionLogs::insert($records);
        });
    }
}