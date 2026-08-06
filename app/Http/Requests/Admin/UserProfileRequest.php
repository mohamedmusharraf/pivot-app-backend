<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country_id'           => ['nullable', 'exists:countries,id'],
            'gender'               => ['nullable', 'string', 'max:50'],
            'birth_year'           => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'set_your_goal'        => ['nullable', 'numeric'],
            'weekly_goal_minutes'  => ['required', 'integer', 'min:0'],
            'onboarding_completed' => ['required', 'boolean'],
        ];
    }
}