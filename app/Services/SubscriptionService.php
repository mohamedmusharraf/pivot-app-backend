<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class SubscriptionService
{
    public function __construct(private readonly RevenueCatService $revenueCat) {}

    public function getAdminData(): array
    {
        $items = Subscription::query()
            ->with(['user', 'tier'])
            ->latest('updated_at')
            ->get()
            ->map(fn(Subscription $subscription): array => $this->toAdminItem($subscription));

        return [
            'items' => $items->all(),
            'source' => 'Database Subscriptions',
        ];
    }

    public function metrics(Collection $items): array
    {
        return [
            'total' => $items->count(),
            'active' => $items->where('status', 'Active')->count(),
            'trial' => $items->where('status', 'Trial')->count(),
            'expired' => $items->where('status', 'Expired')->count(),
            'cancelled' => $items->whereIn('status', ['Cancelled', 'Non-renewing'])->count(),
            'active_revenue' => null,
            'expiring_soon' => $items->filter(fn(array $item): bool => $item['expires_at']
                && Carbon::parse($item['expires_at'])->between(now(), now()->addDays(7)))->count(),
        ];
    }

    public function metricsOverview(): array
    {
        return Cache::remember(
            'admin.revenuecat.metrics-overview',
            now()->addSeconds(60),
            fn(): array => $this->revenueCat->getMetricsOverview()
        );
    }

    private function toAdminItem(Subscription $subscription): array
    {
        $status = $subscription->status;
        if (! $status) {
            $status = $subscription->expires_at?->isPast()
                ? 'Expired'
                : ($subscription->active ? 'Active' : 'Expired');
            if ($subscription->type === 'CANCELLATION') {
                $status = 'Non-renewing';
            } elseif ($subscription->type === 'BILLING_ISSUE') {
                $status = 'Billing issue';
            }
        }

        return [
            'subscription_id' => (string) $subscription->id,
            'user_id' => $subscription->user_id,
            'user_name' => $subscription->user?->name ?? 'Unknown user',
            'user_email' => $subscription->user?->email ?? 'N/A',
            'app_user_id' => $subscription->revenuecat_user_id ?? 'N/A',
            'tier' => $subscription->tier?->name ?? 'N/A',
            'product_id' => $subscription->product_id ?? 'N/A',
            'store' => $this->store($subscription->store),
            'status' => $status,
            'environment' => ucfirst(strtolower((string) ($subscription->environment ?? 'Production'))),
            'price' => null,
            'currency' => null,
            'started_at' => $subscription->started_at,
            'expires_at' => $subscription->expires_at,
            'auto_renew' => $subscription->auto_renew === null ? 'Unknown' : ($subscription->auto_renew ? 'Yes' : 'No'),
            'event_at' => $subscription->revenuecat_event_at,
            'local_subscription_id' => $subscription->id,
        ];
    }

    private function store(?string $store): string
    {
        return match (strtolower((string) $store)) {
            'play_store' => 'Google Play',
            'app_store' => 'App Store',
            'stripe' => 'Stripe',
            'amazon' => 'Amazon',
            default => $store ?: 'Unknown',
        };
    }
}