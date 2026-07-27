<?php

namespace App\Services\AppLogs;

use App\Repositories\Contracts\EmotionLogsRepositoryInterface;
use Illuminate\Support\Carbon;

class EmotionLogsService
{
    public function __construct(
        protected EmotionLogsRepositoryInterface $emotionLogsRepository
    ) {}

    public function storeBatch(int $userId, array $events): bool
    {
        $now = now();
        $records = [];

        foreach ($events as $event) {
            $records[] = [
                'user_id' => $userId,
                'emotion' => $event['emotion'],
                'app_name' => $event['app_name'] ?? null,
                'logged_at' => Carbon::parse($event['logged_at']),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        return $this->emotionLogsRepository->insertBatch($records);
    }
}