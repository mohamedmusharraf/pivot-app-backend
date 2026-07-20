<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFocusSessionLogsRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'started_at' => ['required', 'date_format:Y-m-d H:i:s'],
            'ended_at' => ['nullable', 'date_format:Y-m-d H:i:s', 'after_or_equal:started_at'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'completed' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'started_at.required' => 'The start time is required.',
            'started_at.date_format' => 'The start time must be in the format Y-m-d H:i:s.',
            'ended_at.date_format' => 'The end time must be in the format Y-m-d H:i:s.',
            'ended_at.after_or_equal' => 'The end time must be after or equal to the start time.',
            'duration_minutes.integer' => 'Duration must be an integer.',
            'duration_minutes.min' => 'Duration cannot be negative.',
            'completed.boolean' => 'Completed must be true or false.',
        ];
    }
}
