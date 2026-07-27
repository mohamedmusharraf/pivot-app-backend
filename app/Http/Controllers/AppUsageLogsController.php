<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppUsageLogRequest;
use App\Models\AppUsageLogs;
use App\Services\AppLogs\AppUsageLogsService;
use Illuminate\Http\JsonResponse;

class AppUsageLogsController extends Controller
{
    public function __construct(
        protected AppUsageLogsService $appUsageLogsService
    ) {}

    /**
     * Store a new app usage log.
     */
    public function store(StoreAppUsageLogRequest $request): JsonResponse
    {
        try {
            $this->appUsageLogsService->storeBatch(
                $request->user()->id,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'App usage logs processed and saved successfully.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to store app usage logs.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display all app usage logs for user.
     */
    public function index(): JsonResponse
    {
        try {
            $logs = $this->appUsageLogsService->getUserLogs($this->userId());

            return response()->json([
                'success' => true,
                'data' => $logs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve app usage logs.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a single app usage log.
     */
    public function show(AppUsageLogs $appUsageLog): JsonResponse
    {
        try {
            $log = $this->appUsageLogsService->find($appUsageLog->id);

            return response()->json([
                'success' => true,
                'data' => $log,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve app usage log.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an app usage log.
     */
    public function update(StoreAppUsageLogRequest $request, AppUsageLogs $appUsageLog): JsonResponse
    {
        try {
            $updated = $this->appUsageLogsService->update(
                $appUsageLog,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'App usage log updated successfully.',
                'data' => $updated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update app usage log.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an app usage log.
     */
    public function destroy(AppUsageLogs $appUsageLog): JsonResponse
    {
        try {
            $this->appUsageLogsService->delete($appUsageLog);

            return response()->json([
                'success' => true,
                'message' => 'App usage log deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete app usage log.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get daily usage statistics for user.
     */
    public function dailyStats(): JsonResponse
    {
        try {
            $stats = $this->appUsageLogsService->getUserDailyStats($this->userId());

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve daily statistics.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get app usage summary for user.
     */
    public function summary(): JsonResponse
    {
        try {
            $summary = $this->appUsageLogsService->getAppSummary($this->userId());

            return response()->json([
                'success' => true,
                'data' => $summary,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve app summary.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get authenticated user ID.
     */
    private function userId(): int
    {
        return auth('sanctum')->user()->id;
    }
}
