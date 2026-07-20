<?php 

namespace App\Repositories;

use App\Models\GoalLogs;
use App\Repositories\Contracts\GoalLogsRepositoryInterface;

class GoalLogsRepository implements GoalLogsRepositoryInterface
{
    public function create(array $data)
    {
        return GoalLogs::create($data);
    }
}