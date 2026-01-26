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
    });
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::post('/user/profile', [ProfileController::class, 'store']);
});

Route::prefix('v1')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        // Hobby Management Routes
        Route::get('/hobbies', [HobbyController::class, 'index']);
        Route::get('/hobbies/{hobby}', [HobbyController::class, 'show']);
        Route::post('/hobbies', [HobbyController::class, 'store']);
        Route::put('/hobbies/{hobby}', [HobbyController::class, 'update']);
        Route::delete('/hobbies/{hobby}', [HobbyController::class, 'destroy']);

        Route::post('/user/hobbies', [UserHobbyController::class, 'store']);

        // Activity Management Routes
        Route::get('/activities', [ActivityController::class, 'index']);
        Route::post('/activities', [ActivityController::class, 'store']);
        Route::put('/activities/{activity}', [ActivityController::class, 'update']);
        Route::delete('/activities/{activity}', [ActivityController::class, 'destroy']);
    });
});
