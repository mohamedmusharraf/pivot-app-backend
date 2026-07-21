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

            $records = $this->challengePackRepository->getByUserId($userId);

            // If all records are 'used' (or no records exist), return empty
            $available = $records->filter(fn($r) => $r->status !== 'used');

            return $available->isEmpty() ? collect([]) : $records;
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