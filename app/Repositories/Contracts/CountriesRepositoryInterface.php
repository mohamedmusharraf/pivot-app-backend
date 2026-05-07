<?php

namespace App\Repositories\Contracts;
use App\Models\Country;

interface CountriesRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function update(Country $country, array $data): Country;
    public function delete(Country $country): void;
}
