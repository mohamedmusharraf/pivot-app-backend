<?php

namespace App\Repositories;

use App\Models\AppBlockLog;
use App\Repositories\Contracts\AppBlockLogRepositoryInterface;

class AppBlockLogRepository implements AppBlockLogRepositoryInterface
{
    /**
     * Get all app block logs.
     */
    public function all()
    {
        return AppBlockLog::with('user')->get();
    }

    /**
     * Get app block logs by user.
     */
    public function getByUser(int $userId)
    {
        return AppBlockLog::where('user_id', $userId)
            ->orderByDesc('blocked_at')
            ->get();
    }

    /**
     * Create a new app block log.
     */
    public function create(array $data): AppBlockLog
    {
        return AppBlockLog::create($data);
    }

    /**
     * Update an app block log.
     */
    public function update(AppBlockLog $appBlockLog, array $data): AppBlockLog
    {
        $appBlockLog->update($data);
        return $appBlockLog;
    }

    /**
     * Delete an app block log.
     */
    public function delete(AppBlockLog $appBlockLog): void
    {
        $appBlockLog->delete();
    }

    /**
     * Find app block log by id.
     */
    public function find(int $id): ?AppBlockLog
    {
        return AppBlockLog::with('user')->find($id);
    }

    /**
     * Get statistics for user's blocked apps.
     */
    public function getUserStatistics(int $userId)
    {
        return AppBlockLog::where('user_id', $userId)
            ->selectRaw('app_name, COUNT(*) as total_blocks, SUM(time_saved_minutes) as total_time_saved')
            ->groupBy('app_name')
            ->orderByDesc('total_blocks')
            ->get();
    }
}
