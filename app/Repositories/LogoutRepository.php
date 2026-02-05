<?php

namespace App\Repositories;

use App\Models\Users;
use App\Repositories\Auth\LogoutRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class LogoutRepository implements LogoutRepositoryInterface
{
    public function logoutCurrentUser(): void
    {
        $user = Auth::user();
        if ($user instanceof Users) {
            $this->logout($user);
        }
    }

    public function logout(Users $user): void
    {
        $user->tokens()->delete();
    }
}
