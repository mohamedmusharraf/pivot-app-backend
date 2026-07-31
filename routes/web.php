<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/view-logs', function () {
    return view('viewLogs');
});

Route::get('/app-analyze', function () {
    return view('appAnalyze');
});



Route::get('/invite', function () {
    return view('invite-fallback');
});