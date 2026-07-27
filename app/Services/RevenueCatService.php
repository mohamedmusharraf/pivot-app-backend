<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RevenueCatService
{
    public function grantFreeTrial(string $appUserId, string $os = 'android'): void
    {
        $customer = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.revenuecat.secret_key'),
            'Accept' => 'application/json',
        ])->get("https://api.revenuecat.com/v1/subscribers/{$appUserId}");

        if (! $customer->successful()) {
            throw new \Exception($customer->body());
        }

        if (strtolower($os) === 'ios') {
            $entitlementId = 'tier_3_ios';
        } else {
            $entitlementId = 'tier_3_android';
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.revenuecat.secret_key'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(
            "https://api.revenuecat.com/v1/subscribers/{$appUserId}/entitlements/{$entitlementId}/promotional",
            [
                'duration' => 'weekly',
            ]
        );

        if (! $response->successful()) {
            throw new \Exception($response->body());
        }
    }
}
