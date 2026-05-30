<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\HobbyController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BetaDeviceController;
use App\Http\Controllers\UserHobbyController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\RevenueCatWebhookController;
use App\Http\Controllers\TeamInviteController;
use App\Http\Controllers\UserDailyArticlesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/webhooks/revenuecat', RevenueCatWebhookController::class);

    // Beta testing
    Route::post('/beta-login', [BetaDeviceController::class, 'checkDevice']);
    Route::get('/devices', [BetaDeviceController::class, 'getAll']);
    Route::delete('/devices', [BetaDeviceController::class, 'deleteAll']);
    Route::delete('/devices/{id}', [BetaDeviceController::class, 'deleteById']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/user/current-user', [AuthController::class, 'currentUser']);
        Route::patch('/user/status', [AuthController::class, 'updateStatus']);
        Route::delete('/user/delete', [AuthController::class, 'deleteAccount']);

        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    });
});

Route::prefix('v1')->group(function () {

    Route::get('/invites/token/{token}', [TeamInviteController::class, 'resolveByToken']);
    Route::get('/teams/invites/{token}/preview',[TeamInviteController::class, 'preview']);

    Route::middleware('auth:sanctum')->group(function () {
        // User Profile Routes 
        Route::get('profile/me', [ProfileController::class, 'me']);
        Route::get('profile/countries', [ProfileController::class, 'countries']);
        Route::patch('profile/activities', [ProfileController::class, 'updateActivities']);
        Route::apiResource('profile', ProfileController::class);

        // Hobby Management Routes
        Route::apiResource('hobbies', HobbyController::class);

        Route::apiResource('activities', ActivityController::class);
        Route::get('group-activities', [ActivityController::class, 'groupActivities']);
        Route::get('user-tier-activities', [ActivityController::class, 'userTierActivities']);
        Route::get('user-activities', [ActivityController::class, 'userActivitiesFiltered']);
        Route::get('user-activities/all', [ActivityController::class, 'userActivitiesAll']);
        // User Hobby Management Routes
        Route::apiResource('user/hobbies', UserHobbyController::class);

        // Research Management Routes
        Route::apiResource('research', ResearchController::class);
        Route::get('daily-article', [UserDailyArticlesController::class, 'getDailyArticle']);

        // Countries Routes
        Route::apiResource('countries', CountriesController::class)->only(['index']);

        Route::post('/invites/generate', [TeamInviteController::class, 'generate']);
        Route::post('/invites/code', [TeamInviteController::class, 'joinByCode']);
        Route::post('/invites/accept', [TeamInviteController::class, 'accept']);
        Route::post('/invites/reject', [TeamInviteController::class, 'reject']);
        Route::get('/teams/connections', [TeamInviteController::class, 'connectedUsers']);
        Route::delete('/connections/remove/{connection}', [TeamInviteController::class, 'removeConnection']);
    });
});
