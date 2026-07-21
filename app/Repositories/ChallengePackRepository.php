<?php

namespace App\Repositories;

use App\Models\ChallengePacksWebhook;
use App\Repositories\Contracts\ChallengePackRepositoryInterface;

class ChallengePackRepository implements ChallengePackRepositoryInterface
{
    public function getByUserId(int $userId)
    {
        return ChallengePacksWebhook::where('user_id', $userId)
            ->get();
    }

    public function getChallengePackDetails(int $userId, string $transactionId)
    {
        return ChallengePacksWebhook::where('user_id', $userId)
            ->where('transaction_id', $transactionId)
            ->first();
    }

    public function decrementRemaining(int $userId, string $transactionId, int $usageCount)
    {
        $pack = $this->getChallengePackDetails($userId, $transactionId);

        if ($pack && $pack->remaining > 0) {
            if ($pack->remaining <= $usageCount) {
                $pack->update([
                    'remaining' => 0,
                    'status' => 'used'
                ]);
            } else {
                $pack->decrement('remaining', $usageCount);
            }
            
            return $pack->fresh();
        }
        
        return null;
    }
}
 