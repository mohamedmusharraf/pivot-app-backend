<?php
namespace App\Repositories\Contracts;

use App\Models\UserHobby;
use App\Models\UserProfile;

interface UserProfileRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function update(UserProfile $userProfile, array $data): UserProfile;
    public function delete(UserProfile $userProfile): void;
}