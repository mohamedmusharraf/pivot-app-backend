<?php

namespace App\Repositories\Contracts;

interface ChallengePackRepositoryInterface
{
    public function getChallengePackDetails(int $userId, string $revenueCatEventId);
    public function decrementRemaining(int $userId, string $revenueCatEventId);
}
