<?php

namespace App\Repositories;

use App\Repositories\Contracts\ResearchRepositoryInterface;
use App\Models\Research;
use Illuminate\Support\Collection;

class ResearchRepository implements ResearchRepositoryInterface
{
    public function all(): Collection
    {
        return Research::all();
    }

    public function find(int $id): ?Research
    {
        return Research::find($id);
    }

    public function create(array $data): Research
    {
        return Research::create($data);
    }

    public function update(Research $research, array $data): Research
    {
        $research->update($data);
        return $research;
    }

    public function delete(Research $research): void
    {
        $research->delete();
    }
}
