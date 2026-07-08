<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppBlockLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
    */
    public function rules(): array
    {
        return [
            'app_name' => ['required', 'string', 'max:255'],
            'blocked_at' => ['nullable', 'date'],
            'released_at' => ['nullable', 'date', 'after_or_equal:blocked_at'],
            'attempted' => ['nullable', 'boolean'],
            'success' => ['nullable', 'boolean'],
            'time_saved_minutes' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'app_name.required' => 'The app name is required.',
            'app_name.max' => 'The app name may not be greater than 255 characters.',
            'released_at.after_or_equal' => 'The released at must be after or equal to blocked at.',
            'time_saved_minutes.min' => 'The time saved minutes must be at least 0.',
        ];
    }
}
