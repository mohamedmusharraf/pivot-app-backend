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

        //  TIER  
        if ($this->has('tier')) {
            $tier = trim($this->tier);

            $map = [
                'Tier 1' => '1',
                'Tier 2' => '2',
                'Tier 3' => '3',
            ];

            if (isset($map[$tier])) {
                $tier = $map[$tier];
            }

            $this->merge([
                'tier' => $tier
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
    }

    public function rules(): array
    {
        return [
            'age_suitability' => [
                'nullable',
                'string',
                'regex:/^(\d+\s*-\s*\d+|\d+\s*to\s*\d+|\d+\+)$/i'
            ],

            'tier' => 'nullable|in:1,2,3',

            'mood_match' => 'nullable|array',
            'mood_match.*' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'age_suitability.string' => 'Age suitability must be a text value.',
            'age_suitability.regex' => 'Age suitability must be in a valid format like 8-12, 8 to 12, or 18+.',
            'tier.in' => 'Tier must be one of 1, 2, or 3.',
            'mood_match.array' => 'Mood match must be an array of mood values.',
            'mood_match.*.string' => 'Each mood value must be a valid string.',
        ];
    }
}
