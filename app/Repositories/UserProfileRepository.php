<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserProfileRepositoryInterface;
use Illuminate\Support\Collection;
use App\Models\UserProfile;

class UserProfileRepository implements UserProfileRepositoryInterface{
    public function all(): Collection
    {
        return UserProfile::all();
    }

    public function create(array $data): UserProfile
    {
        return UserProfile::create($data);
    }

    public function findByUserId(int $userId): UserProfile
    {
        return UserProfile::query()
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    public function update(UserProfile $userProfile, array $data): UserProfile
    {
        $userProfile->update($data);
        return $userProfile;
    }

    public function delete(UserProfile $userProfile): void
    {
        $userProfile->delete();
    }
}
