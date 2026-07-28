<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;

class AppleAuthService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected RevenueCatService $revenueCatService,
    ) {}

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

        $appleUser = [
            'sub'   => $decoded->sub,
            'email' => $decoded->email ?? null,
            'name'  => $name ?? 'Apple User',
        ];

        $result = $this->userRepository->findOrCreateByApple($appleUser);

        if ($result['is_new']) {
            // TODO: Re-enable free trial granting when the promo flow is ready again.
            // $this->revenueCatService->grantFreeTrial(
            //     (string) $result['user']->id,
            //     'ios'
            // );
        }

        return $result['user'];
    }
}
