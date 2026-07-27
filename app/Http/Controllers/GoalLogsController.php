<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoalLogsRequest;
use App\Services\AppLogs\GoalLogsService;
use Illuminate\Http\JsonResponse;

class GoalLogsController extends Controller
{
    public function __construct(
        protected GoalLogsService $goalLogsService
    ) {}

    public function store(StoreGoalLogsRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $this->goalLogsService->storeBatch(
                $request->user()->id,
                $validated['events']
            );

            return response()->json([
                'success' => true,
                'message' => 'Goal logs stored successfully.',
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Goal log.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}