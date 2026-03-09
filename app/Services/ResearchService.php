<?php

namespace App\Services;

use App\Models\Research;
use App\Repositories\Contracts\ResearchRepositoryInterface;

class ResearchService{
    public function __construct(
        protected ResearchRepositoryInterface $repository
    ){}
    
    public function list()
    {
        return $this->repository->all();
    }

    public function store(array $data): Research
    {
        return $this->repository->create($data);
    }

    public function update(Research $research, array $data): Research
    {
        return $this->repository->update($research, $data);
    }

    public function delete(Research $research): void
    {
        $this->repository->delete($research);
    }
}
