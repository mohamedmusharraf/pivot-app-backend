<?php

namespace App\Repositories;

use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Auth\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthRepositoryInterface
{
    public function createUser(array $data): Users
    {
        return Users::create([
            'name'     => $data['name'] ?? null,
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'provider' => 'email',
        ]);
    }

    public function findByEmail(string $email): ?Users
    {
        return Users::where('email', $email)->first();
    }

    public function updateLastLogin(Users $user): void
    {
        $user->update(['last_login_at' => now()]);
    }

    public function updateStatusByUserId(int $userId, string $status): Users
    {
        $user = Users::findOrFail($userId);
        $user->status = $status;
        $user->save();

        return $user->fresh(['subscription']);
    }

    public function updatePassword(string $email, string $password): void
    {
        Users::where('email', $email)->update([
            'password' => Hash::make($password),
        ]);
    }

     public function getCurrentUser()
    {
        return Auth::user();
    }
}
