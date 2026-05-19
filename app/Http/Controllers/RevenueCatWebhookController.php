<?php

namespace App\Http\Controllers;

use App\Http\Requests\RevenueCatWebhookRequest;
use App\Models\Subscription;
use App\Models\Tier;
use App\Models\Users;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class RevenueCatWebhookController extends Controller
{
    private const EVENT_INITIAL_PURCHASE = 'INITIAL_PURCHASE';
    private const EVENT_RENEWAL = 'RENEWAL';
    private const EVENT_CANCELLATION = 'CANCELLATION';
    private const EVENT_EXPIRATION = 'EXPIRATION';
    private const EVENT_PRODUCT_CHANGE = 'PRODUCT_CHANGE';
    private const EVENT_BILLING_ISSUE = 'BILLING_ISSUE';

    public function __invoke(RevenueCatWebhookRequest $request): JsonResponse
    {
        if (! $this->isAuthorized($request->header('Authorization'))) {
            return response()->json([
                'message' => 'Unauthorized webhook request.',
            ], 401);
        }

        $payload = $request->validated();
        $userId = $this->resolveUserId($payload['app_user_id']);

        if (! $userId) {
            return response()->json([
                'message' => 'Invalid app_user_id. It must be your numeric user id.',
            ], 422);
        }

        $user = Users::query()->find($userId);

        if (! $user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        $subscription = Subscription::query()->firstWhere('user_id', $user->id);
        $incomingTierId = $this->resolveTierId($payload['product_id'], $subscription?->tier_id);
        $freeTierId = $this->resolveFreeTierId($subscription?->tier_id ?? 1);
        $purchasedAt = $this->fromMilliseconds($payload['purchased_at_ms'] ?? null);
        $expiresAt = $this->fromMilliseconds($payload['expiration_at_ms'] ?? null);
        $eventState = $this->resolveEventState(
            $payload['type'],
            (bool) $payload['active'],
            $incomingTierId,
            $freeTierId,
            $expiresAt,
            $subscription?->expires_at
        );

        $record = Subscription::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'tier_id' => $eventState['tier_id'],
                'type' => $payload['type'],
                'environment' => $payload['environment'] ?? null,
                'active' => $eventState['active'],
                'store' => $payload['store'] ?? null,
                'product_id' => $payload['product_id'],
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

    private function resolveUserId(string $appUserId): ?int
    {
        if (! ctype_digit($appUserId)) {
            return null;
        }

        return (int) $appUserId;
    }

    private function resolveTierId(string $productId, ?int $fallbackTierId): int
    {
        $exactTier = Tier::query()->where('name', $productId)->value('id');

        if ($exactTier) {
            return (int) $exactTier;
        }

        if (preg_match('/(\d+)$/', $productId, $matches) === 1) {
            $number = $matches[1];

            $tierByName = Tier::query()->where('name', $number)->value('id');
            if ($tierByName) {
                return (int) $tierByName;
            }

            $tierById = Tier::query()->find($number);
            if ($tierById) {
                return (int) $tierById->id;
            }
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
        string $eventType,
        bool $incomingActive,
        int $incomingTierId,
        int $freeTierId,
        ?Carbon $incomingExpiresAt,
        ?Carbon $currentExpiresAt
    ): array {
        $effectiveExpiresAt = $incomingExpiresAt ?? $currentExpiresAt;
        $isExpired = $effectiveExpiresAt ? $effectiveExpiresAt->isPast() : false;

        if ($eventType === self::EVENT_EXPIRATION) {
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
