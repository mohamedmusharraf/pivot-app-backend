<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\HobbyRepositoryInterface;
use App\Repositories\HobbyRepository;
use App\Repositories\Contracts\ActivityRepositoryInterface;
use App\Repositories\ActivityRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Contracts\UserHobbyRepositoryInterface;
use App\Repositories\UserHobbyRepository;
use App\Repositories\UserProfileRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Contracts\UserProfileRepositoryInterface;
use App\Repositories\AuthRepository;;
use App\Repositories\PasswordResetRepository;
use App\Repositories\Auth\PasswordResetRepositoryInterface;
use App\Repositories\Auth\LogoutRepositoryInterface;
use App\Repositories\LogoutRepository;

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
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(PasswordResetRepositoryInterface::class, PasswordResetRepository::class);
        $this->app->bind(LogoutRepositoryInterface::class, LogoutRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
