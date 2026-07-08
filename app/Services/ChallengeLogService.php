<?php

namespace App\Services;

use App\Repositories\Contracts\ChallengeLogRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ChallengeLogService
{
    protected $challengeLogRepository;

    public function __construct(ChallengeLogRepositoryInterface $challengeLogRepository)
    {
        $this->challengeLogRepository = $challengeLogRepository;
    }

    public function createChallengeLog(array $data)
    {
        try {
            // Automatically set completed_at if status is 'completed'
            if (isset($data['status']) && $data['status'] === 'completed' && !isset($data['completed_at'])) {
                $data['completed_at'] = now();
            }

            return $this->challengeLogRepository->create($data);

        } catch (\Exception $e) {
            Log::error('Failed to create challenge log: ' . $e->getMessage());
            throw $e;
        }
    }
}
