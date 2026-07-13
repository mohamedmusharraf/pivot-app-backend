<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreActivityLogsRequest;
use App\Services\ActivityLogsService;

class ActivityLogsController extends Controller
{
    public function __construct(
        protected ActivityLogsService $activityLogsService
    ) {}

    public function store(StoreActivityLogsRequest $request)
    {
        try {
            $activityLog = $this->activityLogsService->store($request->validated());

            return response()->json([
                'message' => 'Activity log created successfully.',
                'data' => $activityLog
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to create activity log.',
                'error' => $th->getMessage()
            ], 500);
        };
    }
}
