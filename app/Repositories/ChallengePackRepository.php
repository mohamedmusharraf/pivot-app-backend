<?php

namespace App\Repositories;

use App\Models\ChallengePacksWebhook;
use App\Repositories\Contracts\ChallengePackRepositoryInterface;

class ChallengePackRepository implements ChallengePackRepositoryInterface
{
    public function getChallengePackDetails(int $userId, string $revenueCatEventId)
    {
        return ChallengePacksWebhook::where('user_id', $userId)
            ->where('revenuecat_event_id', $revenueCatEventId)
            ->first();
    }

    public function decrementRemaining(int $userId, string $revenueCatEventId)
    {
        $pack = $this->getChallengePackDetails($userId, $revenueCatEventId);
        
        if ($pack && $pack->remaining > 0) {
            $pack->decrement('remaining');
            $pack->refresh();

            if ($pack->remaining == 0) {
                $pack->update(['status' => 'used']);
            }

            return $pack;
        }
        
        return null;
    }
}
 