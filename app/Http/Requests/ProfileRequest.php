<?php

namespace App\Http\Requests;

use App\Models\Hobby;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (! $this->has('set_your_goal')) {
            if ($this->has('screen_goal_hours')) {
                $this->merge(['set_your_goal' => $this->input('screen_goal_hours')]);
            } elseif ($this->has('screen_goal_minutes')) {
                $this->merge(['set_your_goal' => $this->input('screen_goal_minutes')]);
            } elseif ($this->has('screen_goal')) {
                $this->merge(['set_your_goal' => $this->input('screen_goal')]);
            }
        }

        if ($this->has('date_of_birth') && preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $this->input('date_of_birth'))) {
            $date = \DateTime::createFromFormat('d.m.Y', $this->input('date_of_birth'));

            if ($date !== false) {
                $this->merge(['date_of_birth' => $date->format('Y-m-d')]);
            }
        }

        if ($this->has('category') && ! $this->has('hobby_ids')) {
            $category = $this->input('category');

            if (is_string($category)) {
                $hobby = Hobby::where('name', $category)->first();

                if ($hobby) {
                    $this->merge(['hobby_ids' => [$hobby->id]]);
                }
            } elseif (is_array($category)) {
                $hobbyIds = Hobby::whereIn('name', $category)
                    ->pluck('id')
                    ->toArray();

                if (! empty($hobbyIds)) {
                    $this->merge(['hobby_ids' => $hobbyIds]);
                }
            }
        }

        if ($this->has('activities') && ! $this->has('hobby_ids')) {
            $activities = $this->input('activities');

            if (is_string($activities)) {
                $hobby = Hobby::where('name', $activities)->first();
                if ($hobby) {
                    $this->merge(['hobby_ids' => [$hobby->id]]);
                }
            } elseif (is_array($activities)) {
                $hobbyIds = Hobby::whereIn('name', $activities)
                    ->pluck('id')
                    ->toArray();

                if (! empty($hobbyIds)) {
                    $this->merge(['hobby_ids' => $hobbyIds]);
                }
            }
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
            'gender' => 'required|in:male,female,other,prefer not to say',
            'date_of_birth' => 'required|date|before:today',
            'set_your_goal' => 'required|string|min:1|max:50',
            'category' => 'sometimes|string|exists:hobbies,name',
            'hobby_ids' => 'sometimes|array',
            'hobby_ids.*' => 'integer|exists:hobbies,id',
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
            'set_your_goal.required' => 'Set your goal is required.',
            'set_your_goal.string' => 'Set your goal must be a string.',
            'set_your_goal.min' => 'Set your goal must be at least 1 character.',
            'set_your_goal.max' => 'Set your goal must be 50 characters or less.',
            'category.string' => 'Category must be a string.',
            'category.exists' => 'Selected category is invalid.',
            'onboarding_completed.boolean' => 'Onboarding completed must be a boolean value.',
        ];
    }
}
