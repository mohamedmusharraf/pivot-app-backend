<?php

namespace App\Repositories;

use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Auth\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;

class AuthRepository implements AuthRepositoryInterface
{
    public function createUser(array $data): Users
    {
        return DB::transaction(function () use ($data) {

            $user = Users::create([
                'name'           => $data['name'] ?? null,
                'email'          => $data['email'],
                'password'       => Hash::make($data['password']),
                'provider'       => 'email',
                'status'         => 'not_ready',
                'last_login_at'  => now(),
            ]);

            // Create subscription
            Subscription::create([
                'user_id' => $user->id,
                'tier_id' => 1,
                'active' => true,
            ]);

            return $user;
        });
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
