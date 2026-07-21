<?php

namespace App\Repositories\Contracts;

interface ChallengePackRepositoryInterface
{
    public function getByUserId(int $userId);
    public function getChallengePackDetails(int $userId, string $transactionId);
    public function decrementRemaining(int $userId, string $transactionId, int $usageCount);
}

