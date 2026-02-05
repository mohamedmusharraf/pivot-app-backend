<?php

namespace App\Repositories\Auth;

use App\Models\Users;

interface AuthRepositoryInterface
{
    public function createUser(array $data): Users;
    public function findByEmail(string $email): ?Users;
    public function updateLastLogin(Users $user): void;
    public function updatePassword(string $email, string $password): void;
    public function getCurrentUser();
}
