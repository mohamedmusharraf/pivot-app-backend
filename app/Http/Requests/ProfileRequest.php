<?php

namespace App\Http\Requests;

use App\Models\Hobby;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('set_your_goal') && ! $this->has('weekly_goal_minutes')) {
            $this->merge([
                'weekly_goal_minutes' => (int) $this->input('set_your_goal') * 60
            ]);
        }

        if ($this->has('date_of_birth') && preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $this->input('date_of_birth'))) {
            $date = \DateTime::createFromFormat('d.m.Y', $this->input('date_of_birth'));

            if ($date !== false) {
                $this->merge(['date_of_birth' => $date->format('Y-m-d')]);
            }
        }

        if ($this->has('category') && ! $this->has('hobby_ids')) {
            $category = $this->input('category');

            if (is_array($category)) {
                $hobbyIds = Hobby::whereIn('name', $category)
                    ->pluck('id')
                    ->toArray();

                if (! empty($hobbyIds)) {
                    $this->merge(['hobby_ids' => $hobbyIds]);
                }
            } elseif (is_string($category)) {
                $categoryNames = array_filter(array_map('trim', explode(',', $category)));

                $hobbyIds = Hobby::whereIn('name', $categoryNames)
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
            'country_id' => 'required|integer|exists:countries,id',
            'gender' => 'required|in:male,female,other,prefer not to say',
            'date_of_birth' => 'required|date|before:today',
            'set_your_goal' => 'required|integer|min:1|max:168',
            'category' => 'sometimes|array',
            'category.*' => 'string|exists:hobbies,name',
            'hobby_ids' => 'sometimes|array',
            'hobby_ids.*' => 'integer|exists:hobbies,id',
            'onboarding_completed' => 'sometimes|boolean',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->filled('category')) {
                return;
            }

            $category = $this->input('category');
            $categoryNames = is_array($category) ? $category : array_filter(array_map('trim', explode(',', $category)));

            if (empty($categoryNames)) {
                $validator->errors()->add('category', 'Category must not be empty.');
                return;
            }

            $foundNames = Hobby::whereIn('name', $categoryNames)
                ->pluck('name')
                ->toArray();

            $missing = array_diff($categoryNames, $foundNames);

            if (! empty($missing)) {
                $validator->errors()->add(
                    'category',
                    'The following categories are invalid: ' . implode(', ', $missing)
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required.',
            'user_id.integer' => 'User ID must be an integer.',
            'user_id.exists' => 'The specified user does not exist.',
            'country_id.required' => 'Country ID is required.',
            'country_id.integer' => 'Country ID must be an integer.',
            'country_id.exists' => 'Selected country is invalid.',
            'gender.required' => 'Gender is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Date of birth must be a valid date.',
            'date_of_birth.before' => 'Date of birth must be a date before today.',
            'set_your_goal.required' => 'Set your goal is required.',
            'set_your_goal.integer' => 'Set your goal must be an integer.',
            'set_your_goal.min' => 'Set your goal must be at least 1 hour.',
            'set_your_goal.max' => 'Set your goal must be 168 hours or less.',
            'category.array' => 'Category must be an array.',
            'category.*.string' => 'Each category must be a string.',
            'category.*.exists' => 'Selected category is invalid.',
            'onboarding_completed.boolean' => 'Onboarding completed must be a boolean value.',
        ];
    }
}
