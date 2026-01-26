<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Http\Requests\ActivityRequest;
use App\Http\Resources\ActivityResource;
use App\Repositories\Contracts\ActivityRepositoryInterface;

class ActivityController extends Controller
{
    public function __construct(
        protected ActivityRepositoryInterface $activityRepo
    ) {}

 
    public function index()
    {
        return ActivityResource::collection(
            $this->activityRepo->all()
        );
    }

    public function store(ActivityRequest $request)
    {
        $activity = $this->activityRepo->create(
            $request->validated()
        );

        return response()->json(
            new ActivityResource($activity),
            201
        );
    }

    public function update(ActivityRequest $request, Activity $activity)
    {
        $updatedActivity = $this->activityRepo->update(
            $activity,
            $request->validated()
        );

        return new ActivityResource($updatedActivity);
    }

    public function destroy(Activity $activity)
    {
        $this->activityRepo->delete($activity);

        return response()->json([
            'message' => 'Activity deleted successfully'
        ]);
    }
}
