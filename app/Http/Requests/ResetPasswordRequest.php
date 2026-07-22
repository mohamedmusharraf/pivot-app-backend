<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'       => ['required', 'email'],
            'reset_token' => ['required', 'string'],
            'password'    => [
                'required',
                'confirmed',
                Password::min(8)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'           => 'Email address is required.',
            'email.email'              => 'Please provide a valid email address.',
            'reset_token.required'     => 'Reset token is required.',
            'reset_token.string'       => 'Invalid reset token format.',
            'password.required'        => 'Password is required.',
            'password.confirmed'       => 'Password confirmation does not match.',
            'password.min'             => 'Password must be at least 8 characters.',
        ];
    }
}
