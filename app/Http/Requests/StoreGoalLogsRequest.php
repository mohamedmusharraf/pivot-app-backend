<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoalLogsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'events' => ['required', 'array', 'min:1'],
            'events.*.goal_id' => ['nullable', 'integer', 'exists:goals,id'],
            'events.*.target_minutes' => ['required', 'integer', 'min:0'],
            'events.*.achieved_minutes' => ['required', 'integer', 'min:0'],
            'events.*.completed' => ['nullable', 'boolean'],
            'events.*.completed_at' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'events.required' => 'At least one goal event is required.',
            'events.*.target_minutes.required' => 'Target minutes field is required.',
            'events.*.achieved_minutes.required' => 'Achieved minutes field is required.',
            'events.*.completed_at.required' => 'Completed at date is required.',
            'events.*.completed_at.date' => 'Completed at must be a valid date.',
        ];
    }
}