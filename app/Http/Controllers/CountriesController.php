<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CountryResource;
use App\Services\CountriesService;

class CountriesController extends Controller
{
    public function __construct(
        protected CountriesService $countriesService
    ) {}

    public function index()
    {
        $countries = $this->countriesService->list();
        return CountryResource::collection($countries);
    }

    public function details()
    {
        $countries = $this->countriesService->list();
        return CountryResource::collection($countries);
    }
}
