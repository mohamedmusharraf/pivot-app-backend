<?php

namespace App\Services\AppLogs;

use App\Models\AppUsageLogs;
use App\Repositories\Contracts\AppUsageLogsRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class AppUsageLogsService
{
    public function __construct(
        protected AppUsageLogsRepositoryInterface $repository
    ) {}


    public function store(int $userId, array $data): AppUsageLogs
    {
        $this->validateUsageLogData($data);

        $usageMinutes = $data['usage_minutes'] ?? $this->calculateUsageMinutes(
            $data['started_at'],
            $data['ended_at']
        );

        $usageLogData = [
            'user_id' => $userId,
            'app_name' => $data['app_name'],
            'package_name' => $data['package_name'] ?? null,
            'started_at' => $this->parseDateTime($data['started_at']),
            'ended_at' => $this->parseDateTime($data['ended_at']),
            'usage_minutes' => $usageMinutes,
        ];

        return $this->repository->create($usageLogData);
    }

    public function update(AppUsageLogs $appUsageLog, array $data): AppUsageLogs
    {
        $updateData = [];

        if (isset($data['app_name'])) {
            $updateData['app_name'] = $data['app_name'];
        }

        if (isset($data['package_name'])) {
            $updateData['package_name'] = $data['package_name'];
        }

        if (isset($data['ended_at'])) {
            $updateData['ended_at'] = $this->parseDateTime($data['ended_at']);
        }

        if (isset($data['ended_at']) && isset($appUsageLog->started_at)) {
            $updateData['usage_minutes'] = $this->calculateUsageMinutes(
                $appUsageLog->started_at,
                $data['ended_at']
            );
        } elseif (isset($data['usage_minutes'])) {
            $updateData['usage_minutes'] = $data['usage_minutes'];
        }

        return $this->repository->update($appUsageLog, array_filter($updateData));
    }

    public function delete(AppUsageLogs $appUsageLog): void
    {
        $this->repository->delete($appUsageLog);
    }

    public function getUserLogs(int $userId)
    {
        return $this->repository->getByUser($userId);
    }

    public function getUserDailyStats(int $userId)
    {
        return $this->repository->getUserDailyStats($userId);
    }

    public function getAppSummary(int $userId)
    {
        return $this->repository->getAppSummary($userId);
    }

    public function find(int $id): ?AppUsageLogs
    {
        return $this->repository->find($id);
    }

    private function calculateUsageMinutes($startedAt, $endedAt): int
    {
        $start = $this->parseDateTime($startedAt);
        $end = $this->parseDateTime($endedAt);

        return (int) $start->diffInMinutes($end);
    }

    private function parseDateTime($dateTime)
    {
        if ($dateTime instanceof Carbon) {
            return $dateTime;
        }

        return Carbon::parse($dateTime);
    }

    private function validateUsageLogData(array $data): void
    {
        if (empty($data['app_name'])) {
            throw ValidationException::withMessages([
                'app_name' => 'App name is required.',
            ]);
        }

        if (empty($data['started_at']) || empty($data['ended_at'])) {
            throw ValidationException::withMessages([
                'times' => 'Started at and ended at are required.',
            ]);
        }

        try {
            $startTime = $this->parseDateTime($data['started_at']);
            $endTime = $this->parseDateTime($data['ended_at']);

            if ($endTime->lessThanOrEqualTo($startTime)) {
                throw ValidationException::withMessages([
                    'ended_at' => 'Ended at must be after started at.',
                ]);
            }
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'times' => 'Invalid date format.',
            ]);
        }
    }
}
