<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppBlockLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'events' => ['required', 'array', 'min:1'],
            'events.*.blocked_at' => ['required', 'date'],
            'events.*.released_at' => ['nullable', 'date', 'after_or_equal:events.*.blocked_at'],
            'events.*.time_saved_minutes' => ['required', 'integer', 'min:0'],
            'events.*.apps' => ['required', 'array', 'min:1'],
            'events.*.apps.*.app_name' => ['required', 'string', 'max:255'],
            'events.*.apps.*.package_name' => ['nullable', 'string', 'max:255'],
            'events.*.apps.*.attempted' => ['required', 'boolean'],
            'events.*.apps.*.success' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'events.required' => 'At least one event log is required.',
            'events.*.blocked_at.required' => 'The blocked_at date is required.',
            'events.*.time_saved_minutes.required' => 'Time saved in minutes is required.',
            'events.*.apps.*.app_name.required' => 'The app name is required.',
        ];
    }
}