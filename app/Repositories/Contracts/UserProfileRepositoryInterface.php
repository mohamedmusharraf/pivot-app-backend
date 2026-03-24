<?php
namespace App\Repositories\Contracts;

use App\Models\UserProfile;

interface UserProfileRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function findByUserId(int $userId): UserProfile;
    public function update(UserProfile $userProfile, array $data): UserProfile;
    public function delete(UserProfile $userProfile): void;
}
