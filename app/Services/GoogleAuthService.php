<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\RevenueCatService;
use Exception;
use Google\Client;

class GoogleAuthService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected RevenueCatService $revenueCatService
    ) {}

    public function authenticate(string $idToken, string $os = 'android'): User
    {
        $googleClient = new Client();

        $payload = $googleClient->verifyIdToken($idToken);

        if (!$payload) {
            throw new Exception('Invalid Google token.');
        }

        if (
            empty($payload['iss']) ||
            !in_array($payload['iss'], [
                'accounts.google.com',
                'https://accounts.google.com',
            ], true)
        ) {
            throw new Exception('Invalid token issuer.');
        }

        if (
            empty($payload['aud']) ||
            !in_array(
                $payload['aud'],
                config('services.google.client_ids'),
                true
            )
        ) {
            throw new Exception('Invalid client.');
        }

        if (empty($payload['email_verified'])) {
            throw new Exception('Google email is not verified.');
        }

        $result = $this->userRepository->findOrCreateByGoogle($payload);

        if ($result['is_new']) {

            $this->revenueCatService->grantFreeTrial(
                (string) $result['user']->id,
                $os
            );
        }

        return $result['user'];
    }
}
