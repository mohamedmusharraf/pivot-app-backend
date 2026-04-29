<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('screen_goal') && ! $this->has('screen_goal_minutes')) {
            $this->merge([
                'screen_goal_minutes' => $this->input('screen_goal'),
            ]);
        }
    }

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
            'country' => 'required|string|exists:countries,name',
            'gender'     => 'required|in:male,female,other,prefer not to say',
            'date_of_birth'     => 'required|date|before:today',
            'screen_goal_minutes' => 'required|integer|min:1|max:10080',
            'onboarding_completed' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required.',
            'user_id.integer' => 'User ID must be an integer.',
            'user_id.exists' => 'The specified user does not exist.',
            'country.required' => 'Country is required.',
            'country.exists' => 'Selected country is invalid.',
            'gender.required' => 'Gender is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Date of birth must be a valid date.',
            'date_of_birth.before' => 'Date of birth must be a date before today.',
            'screen_goal_minutes.required' => 'Screen goal minutes is required.',
            'screen_goal_minutes.integer' => 'Screen goal minutes must be a number.',
            'screen_goal_minutes.min' => 'Screen goal minutes must be at least 1.',
            'screen_goal_minutes.max' => 'Screen goal minutes must be 10080 or less.',
            'onboarding_completed.boolean' => 'Onboarding completed must be a boolean value.',
        ];
    }
}
