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

    public function filter(array $filters)
    {
        return Activity::with('hobby')
            ->when(
                $filters['age_suitability'] ?? null,
                fn ($query, $age) => $query->where('age_suitability', $age)
            )
            ->when(
                $filters['tier'] ?? null,
                fn ($query, $tier) => $query->where('tier', $tier)
            )
            ->when(
                $filters['energy_level'] ?? null,
                fn ($query, $level) => $query->where('energy_level', $level)
            )
            ->latest()
            ->get();
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
