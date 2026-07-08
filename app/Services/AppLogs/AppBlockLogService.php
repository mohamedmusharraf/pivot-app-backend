<?php

namespace App\Services\AppLogs;

use App\Models\AppBlockLog;
use App\Repositories\Contracts\AppBlockLogRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class AppBlockLogService
{
    public function __construct(
        protected AppBlockLogRepositoryInterface $repository
    ) {}

    public function store(int $userId, array $data): AppBlockLog
    {
        $this->validateBlockLogData($data);

        $blockLogData = [
            'user_id' => $userId,
            'app_name' => $data['app_name'],
            'blocked_at' => $data['blocked_at'] ?? Carbon::now(),
            'released_at' => $data['released_at'] ?? null,
            'attempted' => $data['attempted'] ?? false,
            'success' => $data['success'] ?? false,
            'time_saved_minutes' => $data['time_saved_minutes'] ?? 0,
        ];

        return $this->repository->create($blockLogData);
    }

    public function update(AppBlockLog $appBlockLog, array $data): AppBlockLog
    {
        $updateData = array_filter([
            'released_at' => $data['released_at'] ?? null,
            'attempted' => $data['attempted'] ?? $appBlockLog->attempted,
            'success' => $data['success'] ?? $appBlockLog->success,
            'time_saved_minutes' => $data['time_saved_minutes'] ?? $appBlockLog->time_saved_minutes,
        ]);

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
