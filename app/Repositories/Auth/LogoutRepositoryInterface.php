<?php
namespace App\Repositories\Auth;

use App\Models\Users;

interface LogoutRepositoryInterface
{
    public function logoutCurrentUser(): void;
}