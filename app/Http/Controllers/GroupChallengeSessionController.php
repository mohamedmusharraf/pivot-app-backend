<?php

namespace App\Http\Controllers;

use App\Services\GroupChallengeSessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupChallengeSessionController extends Controller
{
    public function __construct(
        protected GroupChallengeSessionService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->getForUser($request->user()->id),
        ]);
    }
}
