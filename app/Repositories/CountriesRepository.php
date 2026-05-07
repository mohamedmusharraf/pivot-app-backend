<?php

namespace App\Repositories;

use App\Models\Country;
use App\Repositories\Contracts\CountriesRepositoryInterface;
use Illuminate\Support\Collection;

class CountriesRepository implements CountriesRepositoryInterface
{
    public function all(): Collection
    {
        return Country::query()
            ->select([
                'id as country_id',
                'name as country_name',
            ])
            ->orderBy('name')
            ->get();
    }

    public function create(array $data): Country
    {
        return Country::create($data);
    }

    public function update(Country $country, array $data): Country
    {
        $country->update($data);
        return $country;
    }

    public function delete(Country $country): void
    {
        $country->delete();
    }
}
