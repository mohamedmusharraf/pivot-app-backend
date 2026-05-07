<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CountriesService;

class CountriesController extends Controller
{
    public function __construct(
        protected CountriesService $countriesService
    ){}

    public function index()
    {
        $countries = $this->countriesService->list();
        return response()->json($countries);
    }
}
