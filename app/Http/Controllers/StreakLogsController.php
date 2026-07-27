<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStreakLogsRequest;
use App\Services\AppLogs\StreakLogsService;
use Illuminate\Http\JsonResponse;

class StreakLogsController extends Controller
{
    public function __construct(
        protected StreakLogsService $streakLogsService
    ) {}

    public function store(StoreStreakLogsRequest $request): JsonResponse
    {
        try {
            $streakLog = $this->streakLogsService->store(
                $request->user()->id,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Streak log recorded successfully.',
                'data'    => $streakLog,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record streak log.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}