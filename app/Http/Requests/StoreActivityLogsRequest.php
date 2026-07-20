<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityLogsRequest extends FormRequest
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
            'activity_id' => ['nullable', 'integer'],
            'duration_minutes' => ['nullable', 'integer'],
            'completed' => ['nullable', 'boolean'],
            'completed_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'activity_id.integer' => 'Activity ID must be an integer.',
            'duration_minutes.integer' => 'Duration must be an integer.',
            'completed.boolean' => 'Completed must be a boolean.',
            'completed_at.date_time' => 'Completed at must be a date and time.',
        ];
    }
}
