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
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;
            $activityLog = $this->activityLogsService->store($data);
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
