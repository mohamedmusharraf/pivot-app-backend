<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hobby_id'                => 'nullable|integer',
            'activity_title'          => 'required|string|max:255',
            'instruction'             => 'nullable|string',
            'activity_type'           => 'nullable|string|max:255',
            'subcategory'             => 'nullable|string|max:255',
            'duration_minutes'        => 'nullable|string|max:255',
            'tier'                    => 'nullable|string|max:255',
            'cost'                    => 'nullable|string|max:255',
            'location'                => 'nullable|string|max:255',
            'age_range'               => 'nullable|string|max:255',
            'min_age'                 => 'nullable|integer',
            'max_age'                 => 'nullable|integer',
            'neurodivergent_friendly' => 'nullable|string',
            'neurodivergent_notes'    => 'nullable|string',
            'sensory_tags'            => 'nullable|string|max:255',
            'social_type'             => 'nullable|string|max:255',
            'energy_level'            => 'nullable|string|max:255',
            'outcome_tag'             => 'nullable|string|max:255',
            'time'                    => 'nullable|string|max:255',
            'status'                  => 'nullable|string|max:255',
            'description'             => 'nullable|string',
        ];
    }
}