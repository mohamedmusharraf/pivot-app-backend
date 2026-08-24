<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFcmTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fcm_token' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'fcm_token.required' => 'FCM token is required.',
        ];
    }
}
