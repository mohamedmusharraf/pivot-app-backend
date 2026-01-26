<?php
namespace App\Repositories;

use App\Models\Activity;
use App\Repositories\Contracts\ActivityRepositoryInterface;


class ActivityRepository implements ActivityRepositoryInterface
{
    public function all()
    {
        return Activity::with('hobby')->latest()->get();
    }

    public function create(array $data): Activity
    {
        return Activity::create($data);
    }

    public function update(Activity $activity, array $data): Activity
    {
        $activity->update($data);
        return $activity;
    }

    public function delete(Activity $activity): void
    {
        $activity->delete();
    }
}
