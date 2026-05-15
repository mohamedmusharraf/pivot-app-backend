<?php

namespace App\Repositories;

use App\Models\Activity;
use App\Repositories\Contracts\ActivityRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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

        $allowedTiers = [1];
        if ($subscriptionTierId === 2) {
            $allowedTiers = [1, 2];
        } elseif ($subscriptionTierId >= 3) {
            $allowedTiers = [1, 2, 3];
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

        $allowedSocialTypes = ['Solo', 'Optional Group'];

        if (!empty($filters['social_type'])) {
            $requestedSocialTypes = array_values(array_filter(array_map(function ($type) {
                return is_string($type) ? trim($type) : null;
            }, (array) $filters['social_type'])));

            $socialTypeFilter = array_values(array_intersect($allowedSocialTypes, $requestedSocialTypes));

            if (empty($socialTypeFilter)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('social_type', $socialTypeFilter);
            }
        } else {
            $query->whereIn('social_type', $allowedSocialTypes);
        }

        $categoryNames = [];

        if ($user) {
            $user->loadMissing('hobbies:id,name');
            $categoryNames = $user->hobbies
                ->pluck('name')
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        if (!empty($filters['hobby_names'])) {
            $hobbyNames = array_values(array_filter(array_map(function ($name) {
                return is_string($name) ? trim($name) : null;
            }, (array) $filters['hobby_names'])));

            if (!empty($hobbyNames)) {
                $query->whereHas('hobby', function ($hobbyQuery) use ($hobbyNames) {
                    $hobbyQuery->whereIn('name', $hobbyNames);
                });
            }
        } elseif (!empty($filters['hobby_ids'])) {
            $query->whereIn('hobby_id', array_map('intval', (array) $filters['hobby_ids']));
        } elseif (!empty($categoryNames)) {
            $query->whereHas('hobby', function ($hobbyQuery) use ($categoryNames) {
                $hobbyQuery->whereIn('name', $categoryNames);
            });
        } elseif ($user) {
            $query->whereRaw('1 = 0');
        }

        if (!empty($filters['tier'])) {
            $requestedTiers = array_map('intval', (array) $filters['tier']);
            $tierFilter = array_values(array_intersect($allowedTiers, $requestedTiers));

            if (empty($tierFilter)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('tier', $tierFilter);
            }
        } else {
            $query->whereIn('tier', $allowedTiers);
        }

        $activities = $query->paginate(20)->getCollection();

        return $this->shuffleByHobby($activities);
    }

    public function all()
    {
        return Activity::with('hobby')->get();
    }

    public function groupActivities(int $perPage = 20)
    {
        $query = Activity::query()
            ->with('hobby')
            ->whereIn('social_type', ['Optional Group', 'Group 2-10']);

        $activities = $query->paginate($perPage)->getCollection();

        return $activities;
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

    private function shuffleByHobby(Collection $activities): Collection
    {
        $grouped = $activities
            ->groupBy(fn (Activity $activity) => $activity->hobby_id ?? 0)
            ->map(fn (Collection $group) => $group->shuffle()->values());

        $hobbyKeys = $grouped->keys()->shuffle()->values();
        $shuffled = collect();

        while ($hobbyKeys->isNotEmpty()) {
            foreach ($hobbyKeys as $hobbyKey) {
                /** @var \Illuminate\Support\Collection $bucket */
                $bucket = $grouped->get($hobbyKey, collect());

                if ($bucket->isEmpty()) {
                    continue;
                }

                $shuffled->push($bucket->shift());
                $grouped->put($hobbyKey, $bucket);
            }

            $hobbyKeys = $hobbyKeys
                ->filter(fn ($hobbyKey) => $grouped->get($hobbyKey)?->isNotEmpty())
                ->shuffle()
                ->values();
        }

        return $shuffled->values();
    }
}
