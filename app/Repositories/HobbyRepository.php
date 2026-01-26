<?php

namespace App\Repositories;

use App\Models\Hobby;
use App\Repositories\Contracts\HobbyRepositoryInterface;
use Illuminate\Support\Collection;

class HobbyRepository implements HobbyRepositoryInterface
{
    public function all(): Collection
    {
        return Hobby::with('activities')->get();
    }

    public function find(int $id): ?Hobby
    {
        return Hobby::find($id);
    }

    public function create(array $data): Hobby
    {
        return Hobby::create($data);
    }

    public function update(Hobby $hobby, array $data): Hobby
    {
        $hobby->update($data);
        return $hobby;
    }

    public function delete(Hobby $hobby): void
    {
        $hobby->delete();
    }
}
