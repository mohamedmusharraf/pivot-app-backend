<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
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
            'hobby_id' => 'nullable|exists:hobbies,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'energy_level' => 'nullable|string|min:1|max:5',
            'age_suitability' => 'nullable|string|min:0|max:120',
            'neurodiversity_friendly' => 'nullable|boolean',
        ];
    }
}
