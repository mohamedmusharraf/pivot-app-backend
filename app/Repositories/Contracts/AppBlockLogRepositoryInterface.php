<?php

namespace App\Repositories\Contracts;

use App\Models\AppBlockLog;

interface AppBlockLogRepositoryInterface
{
    public function all();
    public function getByUser(int $userId);
    public function create(array $data): AppBlockLog;
    public function update(AppBlockLog $appBlockLog, array $data): AppBlockLog;
    public function delete(AppBlockLog $appBlockLog): void;
    public function find(int $id): ?AppBlockLog;
    public function getUserStatistics(int $userId);
}
