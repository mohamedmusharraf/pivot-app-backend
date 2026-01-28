<?php

namespace App\Services;

use App\Models\UserProfile;
use App\Repositories\Contracts\UserProfileRepositoryInterface;

class ProfileService
{
    public function __construct(
        protected UserProfileRepositoryInterface $repository
    ) {}

   public function store(array $data): UserProfile
    {
        return $this->repository->create($data);
    }

    public function list()
    {
        return $this->repository->all();
    }

    public function update(UserProfile $profile, array $data): UserProfile
    {
        return $this->repository->update($profile, $data);
    }

    public function delete(UserProfile $profile): void
    {
        $this->repository->delete($profile);
    }
}
