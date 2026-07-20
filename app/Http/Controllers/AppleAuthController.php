<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppleAuthRequest;
use App\Services\AppleAuthService;
use Illuminate\Http\JsonResponse;

class AppleAuthController extends Controller
{
    public function __construct(
        private AppleAuthService $appleAuthService
    ) {}

    public function appleLogin(AppleAuthRequest $request): JsonResponse
    {
        $user = $this->appleAuthService->authenticate(
            $request->identityToken,
            $request->name
        );

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user,
        ]);
    }
}