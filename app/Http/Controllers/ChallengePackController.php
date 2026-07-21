<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\Contracts\ChallengePackRepositoryInterface;

class ChallengePackController extends Controller
{
    private ChallengePackRepositoryInterface $challengePackRepository;

    public function __construct(ChallengePackRepositoryInterface $challengePackRepository)
    {
        $this->challengePackRepository = $challengePackRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer',
            'revenuecat_event_id' => 'required|string',
        ]);

        $details = $this->challengePackRepository->getChallengePackDetails(
            (int) $request->user_id,
            $request->revenuecat_event_id
        );

        if (!$details) {
            return response()->json([
                'message' => 'Challenge pack details not found for the given criteria.'
            ], 404);
        }

        return response()->json([
            'message' => 'Challenge pack details retrieved successfully.',
            'data' => $details
        ], 200);
    }

    public function decrementRemaining(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer',
            'revenuecat_event_id' => 'required|string',
        ]);

        $details = $this->challengePackRepository->decrementRemaining(
            (int) $request->user_id,
            $request->revenuecat_event_id
        );

        if (!$details) {
            return response()->json([
                'message' => 'Challenge pack not found or remaining is already zero.'
            ], 404);
        }

        return response()->json([
            'message' => 'Challenge pack remaining decremented successfully.',
            'data' => $details
        ], 200);
    }
}

