<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class RevenueCatService
{
    public function grantFreeTrial(string $appUserId): void
    {
        $entitlement = 'tier_3_android';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.revenuecat.secret_key'),
            'Content-Type'  => 'application/json',
        ])->post(
            "https://api.revenuecat.com/v1/subscribers/{$appUserId}/entitlements/{$entitlement}/promotional",
            [
                'duration' => 'weekly',
            ]
        );

        Log::info('RevenueCat Response', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        if (! $response->successful()) {
            throw new Exception($response->body());
        }
    }
}