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

    public function getSubscriptions(int $limit = 50): array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/projects/{$this->projectId}/customers", [
                'limit' => $limit,
            ]);

        if ($response->failed()) {
            Log::error('RevenueCat Customers Fetch Error: ' . $response->body());
            return ['items' => []];
        }

        $subscriptions = [];

        foreach ($response->json('items', []) as $customer) {
            $customerId = $customer['id'] ?? null;

            if (! $customerId) {
                continue;
            }

            $customerSubscriptions = $this->getCustomerSubscriptions((string) $customerId, $limit);

            foreach ($customerSubscriptions['items'] ?? [] as $subscription) {
                $subscription['app_user_id'] = $customerId;
                $subscriptions[] = $subscription;
            }
        }

        return ['items' => $subscriptions];
    }

    public function getCustomerSubscriptions(string $customerId, int $limit = 50): ?array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/projects/{$this->projectId}/customers/" . rawurlencode($customerId) . '/subscriptions', [
                'limit' => $limit,
            ]);

        if ($response->failed()) {
            Log::error("RevenueCat Customer Subscriptions Fetch Error for ID {$customerId}: " . $response->body());
            return null;
        }

        return $response->json();
    }

    public function getSubscriptionByIdentifier(string $storeSubscriptionId): array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/projects/{$this->projectId}/subscriptions", [
                'store_subscription_identifier' => $storeSubscriptionId,
            ]);

        if ($response->failed()) {
            Log::error('RevenueCat Subscription Fetch Error: ' . $response->body());
            return [];
        }

        return $response->json();
    }

    protected function headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json',
        ];
    }
}
