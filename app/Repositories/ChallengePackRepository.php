<?php
namespace App\Repositories;

use App\Models\ChallengePacksWebhook;
use App\Repositories\Contracts\ChallengePackRepositoryInterface;

class ChallengePackRepository implements ChallengePackRepositoryInterface
{
    public function getByUserId(int $userId)
    {
        return ChallengePacksWebhook::where('user_id', $userId)->get();
    }

    /**
     * Get only unused challenge packs for the user.
     */
    public function getUnusedByUserId(int $userId)
    {
        return ChallengePacksWebhook::where('user_id', $userId)
            ->where('status', '!=', 'used') // Excludes used packs directly in DB query
            ->get();
    }

    public function getChallengePackDetails(int $userId, string $transactionId)
    {
        return ChallengePacksWebhook::where('user_id', $userId)
            ->where('transaction_id', $transactionId)
            ->where('status', '!=', 'used') // Ensures single record lookup also ignores used packs
            ->first();
    }

    public function decrementRemaining(int $userId, string $transactionId, int $usageCount)
    {
        $pack = ChallengePacksWebhook::where('user_id', $userId)
            ->where('transaction_id', $transactionId)
            ->first();

        if ($pack && $pack->remaining > 0) {
            if ($pack->remaining <= $usageCount) {
                $pack->update([
                    'remaining' => 0,
                    'status'    => 'used'
                ]);
            } else {
                $pack->decrement('remaining', $usageCount);
            }
            
            return $pack->fresh();
        }
        
        return null;
    }
}