<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityLogsRequest;
use App\Services\ActivityLogsService;
use Illuminate\Http\JsonResponse;

class ActivityLogsController extends Controller
{
    public function __construct(
        protected ActivityLogsService $activityLogsService
    ) {}

    public function store(StoreActivityLogsRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            $this->activityLogsService->storeBatch(
                $request->user()->id, 
                $validated['events']
            );

            return response()->json([
                'success' => true,
                'message' => 'Activity logs stored successfully.',
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create activity log.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}