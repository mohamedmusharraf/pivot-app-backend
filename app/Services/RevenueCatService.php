<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class RevenueCatService
{
    public function grantFreeTrial(string $appUserId): void
    {
        // Step 1: Get or create the RevenueCat customer
        $customer = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.revenuecat.secret_key'),
            'Accept' => 'application/json',
        ])->get("https://api.revenuecat.com/v1/subscribers/{$appUserId}");

        if (! $customer->successful()) {
            throw new \Exception($customer->body());
        }

        // Step 2: Grant the promotional entitlement
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.revenuecat.secret_key'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(
            "https://api.revenuecat.com/v1/subscribers/{$appUserId}/entitlements/tier_3_android/promotional",
            [
                'duration' => 'weekly',
            ]
        );

        if (! $response->successful()) {
            throw new \Exception($response->body());
        }
    }
}