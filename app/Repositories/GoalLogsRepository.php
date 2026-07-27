<?php

namespace App\Repositories;

use App\Models\GoalLogs;
use App\Repositories\Contracts\GoalLogsRepositoryInterface;
use Illuminate\Support\Facades\DB;

class GoalLogsRepository implements GoalLogsRepositoryInterface
{
    public function create(array $data)
    {
        return GoalLogs::create($data);
    }

    public function insertBatch(array $records): bool
    {
        return DB::transaction(function () use ($records) {
            return GoalLogs::insert($records);
        });
    }
}
