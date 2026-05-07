<?php

namespace App\Services;

use App\Models\Country;
use App\Repositories\Contracts\CountriesRepositoryInterface;

class CountriesService
{
    public function __construct(
        protected CountriesRepositoryInterface $repository
    ){}

    public function list()
    {
        return $this->repository->all();
    }
}