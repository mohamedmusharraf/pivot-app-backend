<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityLogsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'events' => ['required', 'array', 'min:1'],
            'events.*.activity_id' => ['required', 'integer', 'exists:activities,id'],
            'events.*.duration_minutes' => ['required', 'integer', 'min:0'],
            'events.*.completed' => ['required', 'boolean'],
            'events.*.completed_at' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'events.required' => 'At least one activity event is required.',
            'events.*.activity_id.required' => 'Activity ID is required.',
            'events.*.activity_id.exists' => 'The selected activity does not exist.',
            'events.*.duration_minutes.integer' => 'Duration must be an integer.',
            'events.*.completed.boolean' => 'Completed field must be true or false.',
            'events.*.completed_at.date' => 'Completed at must be a valid date.',
        ];
    }
}