<?php

namespace App\Services;

use App\Repositories\Contracts\ChallengePackRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class ChallengePackService 
{
    protected $challengePackRepository;

    public function __construct(ChallengePackRepositoryInterface $challengePackRepository)
    {
        $this->challengePackRepository = $challengePackRepository;
    }

    public function index(int $userId, ?string $transactionId = null)
    {
        try {
            if ($transactionId) {
                return $this->challengePackRepository->getChallengePackDetails($userId, $transactionId);
            }

            // Fetch only unused records directly
            return $this->challengePackRepository->getUnusedByUserId($userId);
        } catch (Exception $e) {
            Log::error('Error fetching challenge pack details: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $userId, string $transactionId, int $usageCount)
    {
        try {
            return $this->challengePackRepository->decrementRemaining($userId, $transactionId, $usageCount);
        } catch (Exception $e) {
            Log::error('Error updating challenge pack remaining: ' . $e->getMessage());
            throw $e;
        }
    }
}