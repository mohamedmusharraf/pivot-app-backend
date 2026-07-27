<?php

namespace App\Services\AppLogs;

use App\Models\AppBlockLog;
use App\Repositories\AppBlockLogRepository;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AppBlockLogService
{
    protected AppBlockLogRepository $repository;
    public function __construct(AppBlockLogRepository $repository)
    {
        $this->repository = $repository;
    }

    public function storeBatch(int $userId, array $data): bool
    {
        $insertData = [];
        $now = Carbon::now();

        foreach ($data['events'] as $event) {
            $blockedAt = Carbon::parse($event['blocked_at']);
            $releasedAt = isset($event['released_at']) ? Carbon::parse($event['released_at']) : null;
            $timeSavedMinutes = $event['time_saved_minutes'] ?? 0;

            foreach ($event['apps'] as $app) {
                $insertData[] = [
                    'user_id' => $userId,
                    'event_type' => $event['event_type'],
                    'app_name' => $app['app_name'],
                    'package_name' => $app['package_name'] ?? null,
                    'blocked_at' => $blockedAt,
                    'released_at' => $releasedAt,
                    'attempted' => $app['attempted'] ?? false,
                    'success' => $app['success'] ?? false,
                    'time_saved_minutes' => $timeSavedMinutes,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (empty($insertData)) {
            return false;
        }

        return DB::transaction(function () use ($insertData) {
            return $this->repository->insertBatch($insertData);
        });
    }

    public function update(AppBlockLog $appBlockLog, array $data): AppBlockLog
    {
        $updateData = [];

        if (array_key_exists('event_type', $data)) {
            $updateData['event_type'] = $data['event_type'];
        }

        if (array_key_exists('app_name', $data)) {
            $updateData['app_name'] = $data['app_name'];
        }

        if (array_key_exists('package_name', $data)) {
            $updateData['package_name'] = $data['package_name'];
        }

        if (array_key_exists('blocked_at', $data)) {
            $updateData['blocked_at'] = Carbon::parse($data['blocked_at']);
        }

        if (array_key_exists('released_at', $data)) {
            $updateData['released_at'] = $data['released_at'] !== null
                ? Carbon::parse($data['released_at'])
                : null;
        }

        if (array_key_exists('attempted', $data)) {
            $updateData['attempted'] = $data['attempted'];
        }

        if (array_key_exists('success', $data)) {
            $updateData['success'] = $data['success'];
        }

        if (array_key_exists('time_saved_minutes', $data)) {
            $updateData['time_saved_minutes'] = $data['time_saved_minutes'];
        }

        return $this->repository->update($appBlockLog, $updateData);
    }

    public function delete(AppBlockLog $appBlockLog): void
    {
        $this->repository->delete($appBlockLog);
    }

    public function getUserLogs(int $userId)
    {
        return $this->repository->getByUser($userId);
    }

    public function getUserStatistics(int $userId)
    {
        return $this->repository->getUserStatistics($userId);
    }

    public function find(int $id): ?AppBlockLog
    {
        return $this->repository->find($id);
    }

    private function validateBlockLogData(array $data): void
    {
        if (empty($data['app_name'])) {
            throw ValidationException::withMessages([
                'app_name' => 'App name is required.',
            ]);
        }

        if (isset($data['time_saved_minutes']) && $data['time_saved_minutes'] < 0) {
            throw ValidationException::withMessages([
                'time_saved_minutes' => 'Time saved minutes must be a positive number.',
            ]);
        }
    }
}
