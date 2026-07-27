<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFocusSessionLogsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'events' => ['required', 'array', 'min:1'],
            'events.*.started_at' => ['required', 'date'],
            'events.*.ended_at' => ['nullable', 'date', 'after_or_equal:events.*.started_at'],
            'events.*.duration_minutes' => ['nullable', 'integer', 'min:0'],
            'events.*.completed' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'events.required' => 'The events array is required.',
            'events.*.started_at.required' => 'The start time is required for each event.',
            'events.*.started_at.date' => 'The start time must be a valid date/timestamp.',
            'events.*.ended_at.date' => 'The end time must be a valid date/timestamp.',
            'events.*.ended_at.after_or_equal' => 'The end time must be after or equal to the start time.',
            'events.*.duration_minutes.integer' => 'Duration must be an integer.',
            'events.*.completed.boolean' => 'Completed must be true or false.',
        ];
    }
}