<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RevenueCatService
{
    protected string $apiKey;
    protected string $projectId;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.revenuecat.api_key');
        $this->projectId = config('services.revenuecat.project_id');
        $this->baseUrl = config('services.revenuecat.api_url');
    }

    /**
     * Fetch raw subscription list from RevenueCat REST API v2
     */
    public function getSubscriptions(int $limit = 50, ?string $startingAfter = null): array
    {
        $params = ['limit' => $limit];
        if ($startingAfter) {
            $params['starting_after'] = $startingAfter;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])->get("{$this->baseUrl}/projects/{$this->projectId}/subscriptions", $params);

        if ($response->failed()) {
            Log::error('RevenueCat API Fetch Error: ' . $response->body());
            return [];
        }

        return $response->json();
    }
}