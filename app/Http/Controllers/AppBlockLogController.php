<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppBlockLogRequest;
use App\Http\Requests\UpdateAppBlockLogRequest;
use App\Models\AppBlockLog;
use App\Services\AppLogs\AppBlockLogService;
use Illuminate\Http\JsonResponse;

class AppBlockLogController extends Controller
{
    protected AppBlockLogService $appBlockLogService;

    public function __construct(AppBlockLogService $appBlockLogService)
    {
        $this->appBlockLogService = $appBlockLogService;
    }

    /**
     * Store a new app block log.
     */
    public function store(StoreAppBlockLogRequest $request): JsonResponse
    {
        try {
            $this->appBlockLogService->storeBatch(
                $request->user()->id,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'App block logs saved successfully.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create app block log.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an app block log.
     */
    public function update(UpdateAppBlockLogRequest $request, AppBlockLog $appBlockLog): JsonResponse
    {
        try {
            $updated = $this->appBlockLogService->update(
                $appBlockLog,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'App block log updated successfully.',
                'data' => $updated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update app block log.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an app block log.
     */
    public function destroy(AppBlockLog $appBlockLog): JsonResponse
    {
        try {
            $this->appBlockLogService->delete($appBlockLog);

            return response()->json([
                'success' => true,
                'message' => 'App block log deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete app block log.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user's app block logs.
     */
    public function userLogs(): JsonResponse
    {
        try {
            $logs = $this->appBlockLogService->getUserLogs(auth('sanctum')->user()->id);

            return response()->json([
                'success' => true,
                'data' => $logs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve app block logs.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user's app block statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $statistics = $this->appBlockLogService->getUserStatistics(auth('sanctum')->user()->id);

            return response()->json([
                'success' => true,
                'data' => $statistics,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve app block statistics.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
