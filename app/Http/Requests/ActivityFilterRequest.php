<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        
        //  AGE SUITABILITY
        
        if ($this->has('age_suitability')) {
            $value = trim($this->age_suitability);

            $value = str_replace(['–'], '-', $value); 
            $value = preg_replace('/\s+/', ' ', $value);

            $this->merge([
                'age_suitability' => $value,
            ]);
        }

        // MOOD MATCH
     
        if ($this->has('mood_match')) {

            $moods = $this->mood_match;

            if (is_string($moods)) {
                $moods = explode(',', $moods);
            }

            $moods = array_map(function ($m) {
                return ucfirst(strtolower(trim($m)));
            }, $moods);

            $this->merge([
                'mood_match' => $moods
            ]);
        }

        // TIER / HOBBY
        $arrayFields = ['tier', 'hobby_ids', 'hobby_names'];

        foreach ($arrayFields as $field) {
            if (! $this->has($field)) {
                continue;
            }

            $value = $this->input($field);

            if (is_string($value)) {
                $value = array_values(array_filter(array_map('trim', explode(',', $value)), fn ($item) => $item !== ''));
            }

            if (! is_array($value)) {
                $value = [$value];
            }

            $this->merge([$field => $value]);
        }

        if ($this->has('hobby_name') && ! $this->has('hobby_names')) {
            $hobbyName = $this->input('hobby_name');

            if (is_string($hobbyName)) {
                $hobbyName = array_values(array_filter(array_map('trim', explode(',', $hobbyName)), fn ($item) => $item !== ''));
            }

            if (! is_array($hobbyName)) {
                $hobbyName = [$hobbyName];
            }

            $this->merge(['hobby_names' => $hobbyName]);
        }
    }

    public function rules(): array
    {
        return [
            'age_suitability' => [
                'nullable',
                'string',
                'regex:/^(\d+\s*-\s*\d+|\d+\s*to\s*\d+|\d+\+)$/i'
            ],

            'mood_match' => 'nullable|array',
            'mood_match.*' => 'string',
            'tier' => 'nullable|array',
            'tier.*' => 'integer|in:1,2,3',
            'hobby_ids' => 'nullable|array',
            'hobby_ids.*' => 'integer|exists:hobbies,id',
            'hobby_name' => 'nullable|string|max:255',
            'hobby_names' => 'nullable|array',
            'hobby_names.*' => 'string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'age_suitability.string' => 'Age suitability must be a text value.',
            'age_suitability.regex' => 'Age suitability must be in a valid format like 8-12, 8 to 12, or 18+.',
            'mood_match.array' => 'Mood match must be an array of mood values.',
            'mood_match.*.string' => 'Each mood value must be a valid string.',
            'tier.array' => 'Tier filter must be an array.',
            'tier.*.integer' => 'Each tier value must be numeric.',
            'tier.*.in' => 'Tier values must be 1, 2, or 3.',
            'hobby_ids.array' => 'Hobby filter must be an array.',
            'hobby_ids.*.integer' => 'Each hobby id must be numeric.',
            'hobby_ids.*.exists' => 'One or more hobby ids are invalid.',
            'hobby_name.string' => 'Hobby name must be a valid string.',
            'hobby_name.max' => 'Hobby name cannot exceed 255 characters.',
            'hobby_names.array' => 'Hobby names filter must be an array.',
            'hobby_names.*.string' => 'Each hobby name must be a valid string.',
            'hobby_names.*.max' => 'Each hobby name cannot exceed 255 characters.',
        ];
    }
}
