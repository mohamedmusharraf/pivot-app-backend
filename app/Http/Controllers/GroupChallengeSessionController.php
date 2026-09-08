<?php

namespace App\Http\Controllers;

use App\Services\GroupChallengeSessionService;
use Illuminate\Http\JsonResponse;

class GroupChallengeSessionController extends Controller
{
    public function __construct(
        protected GroupChallengeSessionService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->getLeaderboard(),
        ]);
    }
}
