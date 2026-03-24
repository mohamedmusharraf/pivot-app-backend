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
        if ($this->has('age_suitability')) {
            $value = trim($this->age_suitability);

            if ($value === '45') {
                $value = '45+';
            }

            $this->merge([
                'age_suitability' => $value,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'age_suitability' => "nullable|in:16-17,18-30,30-45,45+",
            'tier' => 'nullable|in:Tier 1,Tier 2,Tier 3',
            'energy_level' => 'nullable|in:Easy,Intermediate,Advanced',
        ];
    }
}