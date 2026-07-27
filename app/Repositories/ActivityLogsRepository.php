<?php

namespace App\Repositories;

use App\Models\ActivityLogs;
use App\Repositories\Contracts\ActivityLogsRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ActivityLogsRepository implements ActivityLogsRepositoryInterface
{
    public function insertBatch(array $records): bool
    {
        return DB::transaction(function () use ($records) {
            return ActivityLogs::insert($records);
        });
    }
}