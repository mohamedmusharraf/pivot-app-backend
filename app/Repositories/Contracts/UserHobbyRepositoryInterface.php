<?php
namespace App\Repositories\Contracts;

use App\Models\UserHobby;

interface UserHobbyRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function update(UserHobby $userHobby, array $data): UserHobby;
    public function delete(UserHobby $userHobby): void;
}