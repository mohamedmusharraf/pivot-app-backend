<?php

namespace App\Repositories;

use App\Models\ChallengeLog;
use App\Repositories\Contracts\ChallengeLogRepositoryInterface;

class ChallengeLogRepository implements ChallengeLogRepositoryInterface
{
    public function create(array $data)
    {
        return ChallengeLog::create($data);
    }
}
