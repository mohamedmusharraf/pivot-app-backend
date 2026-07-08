<?php

namespace App\Repositories\Contracts;

use App\Models\AppUsageLogs;

interface AppUsageLogsRepositoryInterface
{
    public function all();
    public function getByUser(int $userId);
    public function create(array $data): AppUsageLogs;
    public function update(AppUsageLogs $appUsageLog, array $data): AppUsageLogs;
    public function delete(AppUsageLogs $appUsageLog): void;
    public function find(int $id): ?AppUsageLogs;
    public function getUserDailyStats(int $userId);
    public function getAppSummary(int $userId);
}
