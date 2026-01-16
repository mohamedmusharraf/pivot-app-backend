<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'country' => 'required|string|max:100',
            'gender'     => 'required|in:male,female,other',
            'age_range'     => 'required|in:5-18,18-30,30-45,45+',
            'screen_goal_hours' => 'required|integer|min:1|max:168',
            'onboarding_completed' => 'sometimes|boolean',
        ];
    }
}
