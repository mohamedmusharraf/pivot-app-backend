<?php

namespace App\Http\Controllers;

use App\Http\Requests\RevenueCatWebhookRequest;
use App\Models\Subscription;
use App\Models\Tier;
use App\Models\Users;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class RevenueCatWebhookController extends Controller
{
    private const EVENT_INITIAL_PURCHASE = 'INITIAL_PURCHASE';
    private const EVENT_RENEWAL = 'RENEWAL';
    private const EVENT_CANCELLATION = 'CANCELLATION';
    private const EVENT_EXPIRATION = 'EXPIRATION';
    private const EVENT_PRODUCT_CHANGE = 'PRODUCT_CHANGE';
    private const EVENT_BILLING_ISSUE = 'BILLING_ISSUE';
    private const PRODUCT_TIER_MAP = [
        'tier_2' => 2,
        'tier_3' => 3,
        'tier_2:tier2' => 2,
        'tier_3:tier3' => 3,
        'tier_2_android' => 2,
        'tier_3_android' => 3,
        'tier_2_ios' => 2,
        'tier_3_ios' => 3,
    ];

    public function __invoke(RevenueCatWebhookRequest $request): JsonResponse
    {
        if (! $this->isAuthorized($request->header('Authorization'))) {
            return response()->json([
                'message' => 'Unauthorized webhook request.',
            ], 401);
        }

        $payload = $request->validated();
        $user = $this->resolveUser($payload);

        if (! $user) {
            return response()->json([
                'message' => 'Unable to resolve user from RevenueCat ids. Send your numeric user id as app_user_id, or ensure this RevenueCat user id is already linked to a subscription.',
            ], 422);
        }

        $subscription = Subscription::query()->firstWhere('user_id', $user->id);
        $eventType = $payload['type'] ?? null;
        $productId = $payload['product_id'] ?? null;
        $entitlementId = $this->resolvePrimaryEntitlementId($payload['entitlement_ids'] ?? null);
        $incomingActive = $request->boolean('active');

        $tierIdentifier = $productId ?: $entitlementId;
        $incomingTierId = $this->resolveTierId($tierIdentifier, $subscription?->tier_id);
        $freeTierId = $this->resolveFreeTierId($subscription?->tier_id ?? 1);
        $purchasedAt = $this->fromMilliseconds($payload['purchased_at_ms'] ?? null);
        $expiresAt = $this->fromMilliseconds($payload['expiration_at_ms'] ?? null);
        $eventState = $this->resolveEventState(
            $eventType,
            $incomingActive,
            $incomingTierId,
            $freeTierId,
            $expiresAt,
            $subscription?->expires_at
        );

        $record = Subscription::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'tier_id' => $eventState['tier_id'],
                'type' => $eventType,
                'environment' => $payload['environment'] ?? null,
                'active' => $eventState['active'],
                'store' => $payload['store'] ?? null,
                'product_id' => $productId ?? $entitlementId,
                'revenuecat_user_id' => $payload['app_user_id'],
                'started_at' => $purchasedAt ?? $subscription?->started_at,
                'expires_at' => $expiresAt ?? $subscription?->expires_at,
            ]
        );

        return response()->json([
            'message' => 'Subscription synced successfully.',
            'subscription_id' => $record->id,
        ], 200);
    }

    private function isAuthorized(?string $authorizationHeader): bool
    {
        $secret = (string) config('services.revenuecat.webhook_secret', '');

        if ($secret === '') {
            return true;
        }

        if (! $authorizationHeader) {
            return false;
        }

        $expected = 'Bearer ' . $secret;

        return hash_equals($expected, trim($authorizationHeader));
    }

    private function resolveUser(array $payload): ?Users
    {
        $candidateIds = array_filter([
            $payload['app_user_id'] ?? null,
            $payload['original_app_user_id'] ?? null,
            ...Arr::wrap($payload['aliases'] ?? []),
        ], static fn($value) => is_string($value) && $value !== '');

        foreach ($candidateIds as $candidateId) {
            $user = $this->resolveUserFromCandidateId($candidateId);

            if ($user) {
                return $user;
            }
        }

        return null;
    }

    private function resolveUserFromCandidateId(string $candidateId): ?Users
    {
        if (ctype_digit($candidateId)) {
            return Users::query()->find((int) $candidateId);
        }

        $subscription = Subscription::query()
            ->where('revenuecat_user_id', $candidateId)
            ->first();

        if (! $subscription) {
            return null;
        }

        return Users::query()->find($subscription->user_id);
    }

    private function resolveTierId(?string $productId, ?int $fallbackTierId): int
    {
        if (! $productId) {
            return $fallbackTierId ?? 1;
        }

        if (array_key_exists($productId, self::PRODUCT_TIER_MAP)) {
            return $this->resolveTierIdFromNumber(self::PRODUCT_TIER_MAP[$productId], $fallbackTierId);
        }

        $exactTier = Tier::query()->where('name', $productId)->value('id');

        if ($exactTier) {
            return (int) $exactTier;
        }

        if (preg_match('/(?:^|_)(\d+)(?:_|$)/', $productId, $matches) === 1) {
            return $this->resolveTierIdFromNumber((int) $matches[1], $fallbackTierId);
        }

        return $fallbackTierId ?? 1;
    }

    private function resolvePrimaryEntitlementId(mixed $entitlementIds): ?string
    {
        if (! is_array($entitlementIds) || $entitlementIds === []) {
            return null;
        }

        foreach ($entitlementIds as $entitlementId) {
            if (is_string($entitlementId) && $entitlementId !== '') {
                return $entitlementId;
            }
        }

        return null;
    }

    private function resolveTierIdFromNumber(int $number, ?int $fallbackTierId): int
    {
        $tierByName = Tier::query()->where('name', (string) $number)->value('id');
        if ($tierByName) {
            return (int) $tierByName;
        }

        $tierById = Tier::query()->find($number);
        if ($tierById) {
            return (int) $tierById->id;
        }

        return $fallbackTierId ?? 1;
    }

    private function fromMilliseconds(int|float|string|null $milliseconds): ?Carbon
    {
        if ($milliseconds === null || $milliseconds === '') {
            return null;
        }

        return Carbon::createFromTimestampMs((float) $milliseconds)->utc();
    }

    private function resolveFreeTierId(int $fallbackTierId): int
    {
        $freeTierByName = Tier::query()->where('name', '1')->value('id');
        if ($freeTierByName) {
            return (int) $freeTierByName;
        }

        $freeTierById = Tier::query()->find(1);
        if ($freeTierById) {
            return (int) $freeTierById->id;
        }

        return $fallbackTierId;
    }

    private function resolveEventState(
        ?string $eventType,
        bool $incomingActive,
        int $incomingTierId,
        int $freeTierId,
        ?Carbon $incomingExpiresAt,
        ?Carbon $currentExpiresAt
    ): array {
        $effectiveExpiresAt = $incomingExpiresAt ?? $currentExpiresAt;
        $isExpired = $effectiveExpiresAt ? $effectiveExpiresAt->isPast() : false;

        if ($eventType === self::EVENT_EXPIRATION) {
            // If RevenueCat sends an EXPIRATION event but the provided expiration
            // timestamp is still in the future, keep the incoming tier as active
            // until the expiration time actually passes. Otherwise downgrade to free.
            if ($incomingExpiresAt && $incomingExpiresAt->isFuture()) {
                return [
                    'tier_id' => $incomingTierId,
                    'active' => true,
                ];
            }

            return [
                'tier_id' => $freeTierId,
                'active' => false,
            ];
        }

        if ($eventType === self::EVENT_CANCELLATION) {
            return [
                'tier_id' => $freeTierId,
                'active' => ! $isExpired,
            ];
        }

        if ($eventType === self::EVENT_BILLING_ISSUE) {
            return [
                'tier_id' => $incomingTierId,
                'active' => ! $isExpired,
            ];
        }

        if (in_array($eventType, [self::EVENT_INITIAL_PURCHASE, self::EVENT_RENEWAL, self::EVENT_PRODUCT_CHANGE], true)) {
            return [
                'tier_id' => $incomingTierId,
                'active' => true,
            ];
        }

        return [
            'tier_id' => $incomingTierId,
            'active' => $incomingActive,
        ];
    }
}
