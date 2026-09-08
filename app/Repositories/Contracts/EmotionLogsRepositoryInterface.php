<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface EmotionLogsRepositoryInterface
{
    public function create(array $data);
    public function insertBatch(array $records): bool;
    public function getEmotionCounts(): Collection;
}
