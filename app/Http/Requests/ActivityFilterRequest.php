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

        // CATEGORY (hobby name)
        if ($this->has('category')) {
            $categories = $this->input('category');

            if (is_string($categories)) {
                $categories = array_filter(array_map('trim', explode(',', $categories)));
            }

            if (is_array($categories)) {
                $categories = array_values(array_filter(array_map(function ($c) {
                    return trim((string) $c);
                }, $categories)));
            } else {
                $categories = [];
            }

            $this->merge([
                'category' => $categories
            ]);
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
            'category' => 'nullable|array',
            'category.*' => 'string|exists:hobbies,name',
        ];
    }

    public function messages(): array
    {
        return [
            'age_suitability.string' => 'Age suitability must be a text value.',
            'age_suitability.regex' => 'Age suitability must be in a valid format like 8-12, 8 to 12, or 18+.',
            'mood_match.array' => 'Mood match must be an array of mood values.',
            'mood_match.*.string' => 'Each mood value must be a valid string.',
            'category.array' => 'Category must be an array or a comma-separated value.',
            'category.*.string' => 'Each category must be a valid string.',
            'category.*.exists' => 'One or more categories are invalid.',
        ];
    }
}
