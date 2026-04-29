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
            // 'country_id' => 'required|integer|max:100',
            'gender'     => 'required|in:male,female,other,prefer_not_to_say',
            'date_of_birth'     => 'required|date|before:today',
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
            // 'country_id.required' => 'Country ID is required.',
            // 'country_id.integer' => 'Country ID must be an integer.',
            // 'country_id.max' => 'Country ID must not exceed 100.',   
            'gender.required' => 'Gender is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Date of birth must be a valid date.',
            'date_of_birth.before' => 'Date of birth must be a date before today.',
            'screen_goal_hours.required' => 'Screen goal hours is required.',
            'onboarding_completed.boolean' => 'Onboarding completed must be a boolean value.',
        ];
    }
}
