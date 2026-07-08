<?php

namespace App\Repositories;

use App\Models\AppUsageLogs;
use App\Repositories\Contracts\AppUsageLogsRepositoryInterface;

class AppUsageLogsRepository implements AppUsageLogsRepositoryInterface
{
    /**
     * Get all app usage logs.
     */
    public function all()
    {
        return AppUsageLogs::with('user')->get();
    }

    /**
     * Get app usage logs by user.
     */
    public function getByUser(int $userId)
    {
        return AppUsageLogs::where('user_id', $userId)
            ->orderByDesc('started_at')
            ->get();
    }

    /**
     * Create a new app usage log.
     */
    public function create(array $data): AppUsageLogs
    {
        return AppUsageLogs::create($data);
    }

    /**
     * Update an app usage log.
     */
    public function update(AppUsageLogs $appUsageLog, array $data): AppUsageLogs
    {
        $appUsageLog->update($data);
        return $appUsageLog;
    }

    /**
     * Delete an app usage log.
     */
    public function delete(AppUsageLogs $appUsageLog): void
    {
        $appUsageLog->delete();
    }

    /**
     * Find app usage log by id.
     */
    public function find(int $id): ?AppUsageLogs
    {
        return AppUsageLogs::with('user')->find($id);
    }

    /**
     * Get user's daily usage statistics.
     */
    public function getUserDailyStats(int $userId)
    {
        return AppUsageLogs::where('user_id', $userId)
            ->selectRaw('DATE(started_at) as usage_date, SUM(usage_minutes) as total_minutes')
            ->groupBy('usage_date')
            ->orderByDesc('usage_date')
            ->get();
    }

    /**
     * Get app usage summary for user.
     */
    public function getAppSummary(int $userId)
    {
        return AppUsageLogs::where('user_id', $userId)
            ->selectRaw('app_name, COUNT(*) as total_sessions, SUM(usage_minutes) as total_minutes, AVG(usage_minutes) as avg_minutes')
            ->groupBy('app_name')
            ->orderByDesc('total_minutes')
            ->get();
    }
}
