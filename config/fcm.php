<?php

return [
    'project_id' => env('FIREBASE_PROJECT_ID'),

    'credentials_path' => env(
        'FIREBASE_CREDENTIALS_PATH',
        storage_path('firebase.json')
    ),

    'model' => App\Models\User::class,
    'table' => 'users',

    'api_url' => null,

    'defaults' => [
        'sound' => 'default',
        'priority' => 'normal',
        'time_to_live' => 86400,
    ],

    'test_token' => env('FCM_TEST_TOKEN'),
    'logging_enabled' => env('FCM_LOGGING_ENABLED', false),
    'throw_exceptions' => env('FCM_THROW_EXCEPTIONS', false),
];
