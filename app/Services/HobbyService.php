<?php
namespace App\Services;

use App\Models\Hobby;
use App\Repositories\Contracts\HobbyRepositoryInterface;

class HobbyService
{
    public function __construct(
        protected HobbyRepositoryInterface $repository
    ) {}

    public function list()
    {
        return $this->repository->all();
    }

    public function store(array $data): Hobby
    {
        return $this->repository->create($data);
    }

    public function update(Hobby $hobby, array $data): Hobby
    {
        return $this->repository->update($hobby, $data);
    }

    public function delete(Hobby $hobby): void
    {
        $this->repository->delete($hobby);
    }
}
