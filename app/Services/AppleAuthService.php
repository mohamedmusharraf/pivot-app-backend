<?php

namespace App\Services;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Subscription;

class AppleAuthService
{
    public function authenticate(string $idToken, ?string $name = null): User
    {
        $keys = Http::get('https://appleid.apple.com/auth/keys')->json();

        $decoded = JWT::decode(
            $idToken,
            JWK::parseKeySet($keys)
        );

        if ($decoded->aud !== config('services.apple.client_id')) {
            throw new \Exception('Invalid Apple Client ID.');
        }

        $appleId = $decoded->sub;
        $email = $decoded->email ?? null;

        $user = User::where('provider', 'apple')
            ->where('provider_id', $appleId)
            ->first();

        if (!$user && $email) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {

            $user = User::create([
                'name' => $name ?? 'Apple User',
                'email' => $email,
                'password' => bcrypt(Str::random(16)),
                'provider' => 'apple',
                'provider_id' => $appleId,
                'status' => 'not_ready',
                'last_login_at' => now(),
            ]);

            $user->profile()->create([
                'onboarding_completed' => false,
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

        } else {

            $user->update([
                'provider' => 'apple',
                'provider_id' => $appleId,
                'last_login_at' => now(),
            ]);
        }

        return $user;
    }
}