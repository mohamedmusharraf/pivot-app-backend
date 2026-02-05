<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\HobbyController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\UserHobbyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/user/current-user', [AuthController::class, 'currentUser']);

        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    });
});

Route::prefix('v1')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        // User Profile Routes
        Route::apiResource('profile', ProfileController::class);

        // Hobby Management Routes
        Route::apiResource('hobbies', HobbyController::class);

        // Activity Management Routes
        Route::apiResource('activities', ActivityController::class);

        // User Hobby Management Routes
        Route::apiResource('user/hobbies', UserHobbyController::class);
    });
});
