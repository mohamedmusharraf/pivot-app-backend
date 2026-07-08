<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivitySearchRequest extends FormRequest
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
            'activity_title' => 'nullable|string|max:255',
            'hobby_ids' => 'nullable|array',
            'hobby_ids.*' => 'integer|exists:hobbies,id',
            'tier' => 'nullable|integer',
            'social_type' => 'nullable|string',
            'mood_match' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
