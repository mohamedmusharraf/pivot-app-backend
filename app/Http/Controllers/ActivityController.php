<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Http\Requests\ActivityRequest;
use App\Http\Resources\ActivityResource;
use App\Services\ActivityService;

class ActivityController extends Controller
{
    public function __construct(
        protected ActivityService $activityService
    ) {}

    public function index(Activity $activity)
    {
        $activity = $this->activityService->list();
        return response()->json([
           ActivityResource::collection($activity->all())
        ]);
    }

    public function show(Activity $activity)
    {
        return response()->json([
            new ActivityResource($activity)
        ]);
    }

    public function store(ActivityRequest $request)
    {
        $activity = $this->activityService->store(
            $request->validated()
        );

        return response()->json([
            new ActivityResource($activity)
        ], 201);
    }

    public function update(ActivityRequest $request, Activity $activity)
    {
        $updatedActivity = $this->activityService->update(
            $activity,
            $request->validated()
        );

        return response()->json([
            new ActivityResource($updatedActivity)
        ]);
    }

    public function destroy(Activity $activity)
    {
        $this->activityService->delete($activity);

        return response()->json([
            'message' => 'Activity deleted successfully'
        ]);
    }
}
