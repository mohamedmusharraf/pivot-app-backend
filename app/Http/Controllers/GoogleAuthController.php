<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoogleAuthRequest;
use App\Http\Resources\UserResource;
use App\Services\GoogleAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class GoogleAuthController extends Controller
{
    public function __construct(
        protected GoogleAuthService $googleAuthService
    ) {}

    public function googleLogin(GoogleAuthRequest $request): JsonResponse
    {
        try {
            $user = $this->googleAuthService->authenticate(
                $request->validated('id_token'),
                $request->validated('platform') ?? 'android'
            );

            $token = $user->createToken('mobile')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User authenticated successfully.',
                'data' => [
                    'user' => new UserResource($user),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Google login failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (app()->isLocal()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

            return response()->json([
                'success' => false,
                'message' => 'Authentication failed.',
            ], 401);
        }
    }
}
