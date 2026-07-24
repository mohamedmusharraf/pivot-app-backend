<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;

class UserRepository
{
    /**
     * Find or create a user by Google profile data.
     *
     * @param array $googleUser
     * @return User
     */
    public function findOrCreateByGoogle(array $googleUser): array
    {
        $user = User::where('email', $googleUser['email'])->first();

        if ($user) {

            if (!$user->provider_id) {
                $user->update([
                    'provider' => 'google',
                    'provider_id' => $googleUser['sub'],
                ]);
            }

            $user->update([
                'last_login_at' => now(),
            ]);

            return [
                'user' => $user,
                'is_new' => false,
            ];
        }

        $user = DB::transaction(function () use ($googleUser) {

            $user = User::create([
                'name' => $googleUser['name'] ?? explode('@', $googleUser['email'])[0],
                'email' => $googleUser['email'],
                'password' => Hash::make(Str::random(32)),
                'provider' => 'google',
                'provider_id' => $googleUser['sub'],
                'status' => 'not_ready',
                'last_login_at' => now(),
            ]);

            Subscription::create([
                'user_id' => $user->id,
                'tier_id' => 1,
                'active' => true,
            ]);

            return $user;
        });

        return [
            'user' => $user,
            'is_new' => true,
        ];
    }
}
