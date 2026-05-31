<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RevenueCatWebhookRequest extends FormRequest
{
    private const EVENT_TYPES = [
        'INITIAL_PURCHASE',
        'RENEWAL',
        'CANCELLATION',
        'EXPIRATION',
        'PRODUCT_CHANGE',
        'BILLING_ISSUE',
    ];

    private const SUPPORTED_IOS_TIER_PRODUCTS = [
        'tier_2_ios',
        'tier_3_ios',
    ];

    private const SUPPORTED_ANDROID_TIER_PRODUCTS = [
        'tier_2',
        'tier_3',
        'tier_2_android',
        'tier_3_android',
    ];

    protected function prepareForValidation(): void
    {
        $payload = $this->input('event');

        if (is_array($payload)) {
            $this->merge($payload);
        }
    }

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'app_user_id' => ['required', 'string'],
            'original_app_user_id' => ['nullable', 'string'],
            'aliases' => ['nullable', 'array'],
            'aliases.*' => ['string'],
            'product_id' => [
                'nullable',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! is_string($value) || $value === '') {
                        return;
                    }

                    // Validate known iOS tier product ids used by RevenueCat.
                    if (
                        str_ends_with($value, '_ios') && str_starts_with($value, 'tier_')
                        && ! in_array($value, self::SUPPORTED_IOS_TIER_PRODUCTS, true)
                    ) {
                        $fail("The {$attribute} is not a supported iOS tier product.");
                    }

                    // Validate known Android tier product ids used by RevenueCat.
                    if ((str_ends_with($value, '_android') || preg_match('/^tier_\d+$/', $value) === 1)
                        && ! in_array($value, self::SUPPORTED_ANDROID_TIER_PRODUCTS, true)
                    ) {
                        $fail("The {$attribute} is not a supported Android tier product.");
                    }
                },
            ],
            'entitlement_ids' => ['nullable', 'array'],
            'entitlement_ids.*' => ['string', 'max:255'],
            'type' => ['nullable', Rule::in(self::EVENT_TYPES)],
            'new_product_id' => [
                'nullable',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! is_string($value) || $value === '') {
                        return;
                    }

                    // Validate known iOS tier product ids used by RevenueCat.
                    if (
                        str_ends_with($value, '_ios') && str_starts_with($value, 'tier_')
                        && ! in_array($value, self::SUPPORTED_IOS_TIER_PRODUCTS, true)
                    ) {
                        $fail("The {$attribute} is not a supported iOS tier product.");
                    }

                    // Validate known Android tier product ids used by RevenueCat.
                    if ((str_ends_with($value, '_android') || preg_match('/^tier_\d+$/', $value) === 1)
                        && ! in_array($value, self::SUPPORTED_ANDROID_TIER_PRODUCTS, true)
                    ) {
                        $fail("The {$attribute} is not a supported Android tier product.");
                    }
                },
            ],
            'environment' => ['nullable', 'string', 'max:50'],
            'active' => ['nullable', 'boolean'],
            'store' => ['nullable', 'string', 'max:50'],
            'purchased_at_ms' => ['nullable', 'numeric'],
            'expiration_at_ms' => ['nullable', 'numeric'],
        ];
    }
}
