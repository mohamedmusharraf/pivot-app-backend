<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\ChallengePackService;

class ChallengePackController extends Controller
{
    private ChallengePackService $challengePackService;

    public function __construct(ChallengePackService $challengePackService)
    {
        $this->challengePackService = $challengePackService;
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'user_id'        => 'required|integer',
            'transaction_id' => 'nullable|string',
        ]);

        $result = $this->challengePackService->index(
            (int) $request->user_id,
            $request->transaction_id
        );

        // Single record lookup (user_id + transaction_id)
        if ($request->filled('transaction_id')) {
            if (!$result) {
                return response()->json([
                    'message' => 'Challenge pack not found for the given criteria.'
                ], 404);
            }

            return response()->json([
                'message' => 'Challenge pack details retrieved successfully.',
                'data'    => $result
            ], 200);
        }

        // All-records lookup (user_id only)
        return response()->json([
            'message' => 'Challenge packs retrieved successfully.',
            'data'    => $result
        ], 200);
    }

    public function decrementRemaining(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer',
            'transaction_id' => 'required|string',
            'usage_count' => 'required|integer'
        ]);

        $details = $this->challengePackService->update(
            (int) $request->user_id,
            $request->transaction_id,
            (int) $request->usage_count
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


