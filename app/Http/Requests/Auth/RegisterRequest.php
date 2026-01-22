<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'name'     => 'nullable|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.string'                      => 'Name must be a valid string.',
            'name.max'                         => 'Name cannot exceed 255 characters.',
            'email.required'                   => 'Email address is required.',
            'email.email'                      => 'Please provide a valid email address.',
            'email.unique'                     => 'This email address is already registered. Please login or use a different email.',
            'password.required'                => 'Password is required.',
            'password.string'                  => 'Password must be a valid string.',
            'password.min'                     => 'Password must be at least 6 characters.',
            'password.confirmed'               => 'Password confirmation does not match.',
            'password_confirmation.required'   => 'Password confirmation is required.',
            'password_confirmation.string'     => 'Password confirmation must be a valid string.',
            'password_confirmation.min'        => 'Password confirmation must be at least 6 characters.',
        ];
    }
}
