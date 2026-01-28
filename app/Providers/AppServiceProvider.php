<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\HobbyRepositoryInterface;
use App\Repositories\HobbyRepository;
use App\Repositories\Contracts\ActivityRepositoryInterface;
use App\Repositories\ActivityRepository;
use App\Repositories\Contracts\UserHobbyRepositoryInterface;
use App\Repositories\UserHobbyRepository;
use App\Repositories\UserProfileRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Contracts\UserProfileRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(HobbyRepositoryInterface::class, HobbyRepository::class);
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(UserHobbyRepositoryInterface::class, UserHobbyRepository::class);  
        $this->app->bind(UserProfileRepositoryInterface::class, UserProfileRepository::class);  
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
