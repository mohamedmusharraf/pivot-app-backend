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
            'hobby_id'                => ['nullable', 'exists:hobbies,id'],
            'activity_title'          => ['required', 'string', 'max:255'],
            'description'             => ['nullable', 'string'],
            'instruction'             => ['nullable', 'string'],
            'activity_type'           => ['nullable', 'string', 'max:100'],
            'subcategory'             => ['nullable', 'string', 'max:100'],
            'duration_minutes'        => ['nullable', 'integer', 'min:1'],
            'tier'                    => ['nullable', 'string', 'max:50'],
            'cost'                    => ['nullable', 'string', 'max:50'],
            'location'                => ['nullable', 'string', 'max:255'],
            'age_range'               => ['nullable', 'string', 'max:50'],
            'neurodivergent_friendly' => ['nullable', 'boolean'],
            'neurodivergent_notes'    => ['nullable', 'string'],
            'sensory_tags'            => ['nullable', 'string'],
            'social_type'             => ['nullable', 'string', 'max:100'],
            'energy_level'            => ['nullable', 'string', 'max:50'],
            'outcome_tag'             => ['nullable', 'string', 'max:100'],
            'mood_match'              => ['nullable', 'array'],
        ];
    }
}