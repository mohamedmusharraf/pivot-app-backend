<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface GroupChallengeSessionRepositoryInterface
{
    public function getForUser(int $userId): Collection;
}
