<?php

namespace App\Repositories;

use App\Models\UserHobby;
use App\Repositories\Contracts\UserHobbyRepositoryInterface;
use Illuminate\Support\Collection;

class UserHobbyRepository implements UserHobbyRepositoryInterface
{
    public function all(): Collection
    {
        return UserHobby::with('hobby.activities')->get();
    }

    public function find(UserHobby $userHobby): UserHobby
    {
        return $userHobby->load('hobby.activities');
    }

    public function create(array $data): UserHobby
    {
        return UserHobby::create($data);
    }

    public function update(UserHobby $userHobby, array $data): UserHobby
    {
        $userHobby->update($data);
        return $userHobby;
    }

    public function delete(UserHobby $userHobby): void
    {
        $userHobby->delete();
    }
}
