<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use App\Models\PromoCodeRedemption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoCodeController extends Controller
{
    public function validateCode(Request $request): JsonResponse
    {
        $data = $this->validatedRequest($request);
        $promo = PromoCode::query()->where('code', $data['code'])->first();

        if (! $promo) {
            return $this->failure('INVALID_CODE', 'This promo code is not valid.');
        }

        $reason = $this->ineligibilityReason($promo, $request->user()->id);
        if ($reason) {
            return $this->failure($reason, $this->messageFor($reason));
        }

        return response()->json([
            'valid' => true,
            'code' => $promo->code,
            'discount_percent' => $promo->discount_percent,
            'product_id' => $promo->applicable_product_id,
            'offer_tag' => $promo->offer_tag,
            'remaining_redemptions' => $this->remainingRedemptions($promo),
            'message' => 'Promo code approved.',
        ]);
    }

    public function confirm(Request $request): JsonResponse
    {
        $data = $this->validatedRequest($request, true);
        $userId = $request->user()->id;

        $existing = PromoCodeRedemption::query()->where('transaction_id', $data['transaction_id'])->first();
        if ($existing) {
            if ($existing->user_id === $userId && $existing->promo_code_id === PromoCode::where('code', $data['code'])->value('id')) {
                return $this->confirmedResponse($existing, true);
            }

            return $this->failure('TRANSACTION_ALREADY_USED', 'This transaction has already been used with a promo code.');
        }

        return DB::transaction(function () use ($data, $userId) {
            $promo = PromoCode::query()->where('code', $data['code'])->lockForUpdate()->first();
            if (! $promo) {
                return $this->failure('INVALID_CODE', 'This promo code is not valid.');
            }

            $reason = $this->ineligibilityReason($promo, $userId);
            if ($reason) {
                return $this->failure($reason, $this->messageFor($reason));
            }

            $redemption = PromoCodeRedemption::query()->create([
                'promo_code_id' => $promo->id,
                'user_id' => $userId,
                'product_id' => null,
                'transaction_id' => $data['transaction_id'],
                'status' => 'redeemed',
                'validated_at' => now(),
                'redeemed_at' => now(),
            ]);

            return $this->confirmedResponse($redemption);
        });
    }

    private function validatedRequest(Request $request, bool $requiresTransaction = false): array
    {
        $request->merge(['code' => strtoupper(trim((string) $request->input('code')))]);

        return $request->validate([
            'code' => ['required', 'string', 'max:100'],
            'transaction_id' => [$requiresTransaction ? 'required' : 'nullable', 'string', 'max:255'],
        ]);
    }

    private function ineligibilityReason(PromoCode $promo, int $userId): ?string
    {
        if (! $promo->active) {
            return 'INACTIVE_CODE';
        }
        if ($promo->expires_at?->isPast()) {
            return 'EXPIRED_CODE';
        }
        if ($promo->assigned_user_id && $promo->assigned_user_id !== $userId) {
            return 'USER_NOT_ELIGIBLE';
        }
        if (! filled($promo->offer_tag)) {
            return 'INACTIVE_CODE';
        }
        if ($this->remainingRedemptions($promo) < 1) {
            return 'REDEMPTION_LIMIT_REACHED';
        }
        if ($promo->one_per_user && $promo->redemptions()->where('user_id', $userId)->where('status', 'redeemed')->exists()) {
            return 'ALREADY_REDEEMED';
        }

        return null;
    }

    private function remainingRedemptions(PromoCode $promo): int
    {
        return max(0, $promo->max_redemptions - $promo->redemptions()->where('status', 'redeemed')->count());
    }

    private function confirmedResponse(PromoCodeRedemption $redemption, bool $idempotent = false): JsonResponse
    {
        $promo = $redemption->promoCode;

        return response()->json([
            'success' => true,
            'code' => $promo->code,
            'status' => $redemption->status,
            'remaining_redemptions' => $this->remainingRedemptions($promo),
            'idempotent' => $idempotent,
        ]);
    }

    private function failure(string $reason, string $message): JsonResponse
    {
        return response()->json(['valid' => false, 'reason' => $reason, 'message' => $message], 422);
    }

    private function messageFor(string $reason): string
    {
        return match ($reason) {
            'INACTIVE_CODE' => 'This promo code is inactive.',
            'EXPIRED_CODE' => 'This promo code has expired.',
            'REDEMPTION_LIMIT_REACHED' => 'This promo code is no longer available.',
            'ALREADY_REDEEMED' => 'You have already used this promo code.',
            'USER_NOT_ELIGIBLE' => 'You are not eligible for this promo code.',
            'PRODUCT_NOT_ELIGIBLE' => 'This promo code cannot be used for this subscription.',
            default => 'This promo code is not valid.',
        };
    }
}
