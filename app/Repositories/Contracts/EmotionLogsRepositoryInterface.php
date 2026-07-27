<?php 

namespace App\Repositories\Contracts;

interface EmotionLogsRepositoryInterface
{
    public function create(array $data);
    public function insertBatch(array $records): bool;
}