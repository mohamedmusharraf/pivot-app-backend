<?php

namespace App\Services;

use App\Models\Activity;
use App\Repositories\Contracts\ActivityRepositoryInterface;

class ActivityService
{
    public function __construct(
        protected ActivityRepositoryInterface $repository
    ) {}

    public function list()
    {
        return $this->repository->all();
    }

    public function store(array $data): Activity
    {
        return $this->repository->create($data);
    }

    public function update(Activity $activity, array $data): Activity
    {
        return $this->repository->update($activity, $data);
    }

    public function delete(Activity $activity): void
    {
        $this->repository->delete($activity);
    }
}
