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
            'product_id' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', Rule::in(self::EVENT_TYPES)],
            'environment' => ['nullable', 'string', 'max:50'],
            'active' => ['nullable', 'boolean'],
            'store' => ['nullable', 'string', 'max:50'],
            'purchased_at_ms' => ['nullable', 'numeric'],
            'expiration_at_ms' => ['nullable', 'numeric'],
        ];
    }
}
