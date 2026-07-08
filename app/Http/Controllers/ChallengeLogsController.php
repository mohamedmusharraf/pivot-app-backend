<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChallengeLogRequest;
use App\Services\ChallengeLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChallengeLogsController extends Controller
{
    protected $challengeLogService;

    public function __construct(ChallengeLogService $challengeLogService)
    {
        $this->challengeLogService = $challengeLogService;
    }

    public function store(StoreChallengeLogRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            $data['user_id'] = $request->user()->id ?? 1; // Fallback to 1 if no auth in testing

            $challengeLog = $this->challengeLogService->createChallengeLog($data);

            return response()->json([
                'success' => true,
                'message' => 'Challenge activity logged successfully.',
                'data' => $challengeLog
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to log challenge activity.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
