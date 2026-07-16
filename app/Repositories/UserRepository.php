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
    public function findOrCreateByGoogle(array $googleUser): User
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

            return $user;
        }

        return DB::transaction(function () use ($googleUser) {

            $user = User::create([
                'name' => $googleUser['name'] ?? explode('@', $googleUser['email'])[0],
                'email' => $googleUser['email'],
                'password' => Hash::make(Str::random(32)),
                'provider' => 'google',
                'provider_id' => $googleUser['sub'],
                'last_login_at' => now(),
                'status' => 'not_ready',
            ]);

            $subscription = Subscription::create([
                'user_id' => $user->id,
                'tier_id' => 1,
                'start_date' => null,
                'end_date' => null,
                'type' => null,
                'environment' => null,
                'active' => true,
                'store' => null,
                'product_id' => null,
                'revenuecat_user_id' => null,
                'started_at' => null,
                'expires_at' => null,
            ]);
            // $user->profile()->create([
            //     'country_id' => null,
            //     'gender' => null,
            //     'birth_year' => null,
            //     'set_your_goal' => 0,
            //     'weekly_goal_minutes' => 0,
            //     'onboarding_completed' => false,
            // ]);

            return $user;
        });
    }
}
