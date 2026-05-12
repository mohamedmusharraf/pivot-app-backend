<?php

namespace App\Repositories;

use App\Models\Activity;
use App\Repositories\Contracts\ActivityRepositoryInterface;
use Carbon\Carbon;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function filter(array $filters, $user)
    {
        $query = Activity::query()->with('hobby');


        if ($user && $user->profile && $user->profile->date_of_birth) {

            $age = Carbon::parse($user->profile->date_of_birth)->age;

            $query->where(function ($q) use ($age) {
                $q->where('min_age', '<=', $age)
                    ->where(function ($q2) use ($age) {
                        $q2->where('max_age', '>=', $age)
                            ->orWhereNull('max_age');
                    });
            });
        }

        $subscriptionTierId = 1;

        if ($user) {
            $user->loadMissing('subscription');
            $subscriptionTierId = (int) ($user->subscription->tier_id ?? 1);
        }

        if ($subscriptionTierId === 1) {
            $query->where('tier', 1);
        } elseif ($subscriptionTierId === 2) {
            $query->whereIn('tier', [1, 2]);
        } elseif ($subscriptionTierId >= 3) {
            $query->whereIn('tier', [1, 2, 3]);
        }

        if (!empty($filters['mood_match'])) {

            $moods = is_array($filters['mood_match'])
                ? $filters['mood_match']
                : [$filters['mood_match']];

            $query->where(function ($q) use ($moods) {
                foreach ($moods as $mood) {
                    $q->orWhereJsonContains('mood_match', $mood);
                }
            });
        }

        $categoryNames = [];

        if (!empty($filters['category'])) {
            $categoryNames = is_array($filters['category'])
                ? $filters['category']
                : [$filters['category']];
        } elseif ($user) {
            $user->loadMissing('hobbies:id,name');
            $categoryNames = $user->hobbies
                ->pluck('name')
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        if (!empty($categoryNames)) {
            $query->whereHas('hobby', function ($hobbyQuery) use ($categoryNames) {
                $hobbyQuery->whereIn('name', $categoryNames);
            });
        } elseif ($user) {
            $query->whereRaw('1 = 0');
        }

        $query->orderByRaw("
            CASE 
                WHEN LOWER(cost) = 'free' THEN 0
                ELSE 1
            END
        ");

        $query->orderBy('tier', 'asc');

        return $query->get();
    }

    public function all()
    {
        return Activity::with('hobby')->get();
    }

    public function create(array $data): Activity
    {
        return Activity::create($data);
    }

    public function update(Activity $activity, array $data): Activity
    {
        $activity->update($data);
        return $activity->fresh();
    }

    public function delete(Activity $activity): void
    {
        $activity->delete();
    }
}
