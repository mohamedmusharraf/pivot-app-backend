<?php
namespace App\Repositories\Contracts;

use App\Models\Activity;

interface ActivityRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function update(Activity $activity, array $data): Activity;
    public function delete(Activity $activity): void;
}
