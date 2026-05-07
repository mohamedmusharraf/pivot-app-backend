<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserProfileRepositoryInterface;
use Illuminate\Support\Collection;
use App\Models\UserProfile;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class UserProfileRepository implements UserProfileRepositoryInterface
{
    public function all(): Collection
    {
        return UserProfile::query()
            ->with(['user:id,name,email', 'country:id,name', 'hobbies:id,name'])
            ->get();
    }

    public function create(array $data): UserProfile
    {
        return DB::transaction(function () use ($data) {
            $profile = UserProfile::create($data);

            $defaultTierId = 1;

            Subscription::create([
                'user_id' => $data['user_id'],
                'tier_id' => $defaultTierId,
                'active' => true,
                'store' => null,
                'product_id' => null,
                'revenuecat_user_id' => null,
                'started_at' => null,
                'expires_at' => null,
            ]);

            return $profile;
        });
    }

    public function findByUserId(int $userId): UserProfile
    {
        return UserProfile::query()
            ->with(['user:id,name,email', 'country:id,name', 'hobbies:id,name'])
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
