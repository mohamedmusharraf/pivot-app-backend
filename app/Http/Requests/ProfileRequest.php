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
            'user_id' => 'required|integer|exists:users,id', 
            'country_id' => 'required|integer|max:100',
            'gender'     => 'required|in:male,female,other',
            'age_range'     => 'required|in:5-18,18-30,30-45,45+',
            'screen_goal_hours' => 'required|integer|min:1|max:168',
            'onboarding_completed' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required.',
            'user_id.integer' => 'User ID must be an integer.',
            'user_id.exists' => 'The specified user does not exist.',
            'country_id.required' => 'Country ID is required.',
            'country_id.integer' => 'Country ID must be an integer.',
            'country_id.max' => 'Country ID must not exceed 100.',
            'gender.required' => 'Gender is required.',
            'age_range.required' => 'Age range is required.',
            'screen_goal_hours.required' => 'Screen goal hours is required.',
            'onboarding_completed.boolean' => 'Onboarding completed must be a boolean value.',
        ];
    }
}
