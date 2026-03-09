<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;
use App\Models\Research;

interface ResearchRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Research;

    public function create(array $data): Research;

    public function update(Research $research, array $data): Research;

    public function delete(Research $research): void;
}
