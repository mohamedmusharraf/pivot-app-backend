<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppUsageLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'batched_logs' => ['required', 'array', 'min:1'],
            'batched_logs.*.timeframe' => ['required', 'array'],
            'batched_logs.*.timeframe.started_at' => ['required', 'date'],
            'batched_logs.*.timeframe.ended_at' => ['required', 'date', 'after:batched_logs.*.timeframe.started_at'],
            'batched_logs.*.apps' => ['required', 'array', 'min:1'],
            'batched_logs.*.apps.*.app_name' => ['required', 'string', 'max:255'],
            'batched_logs.*.apps.*.package_name' => ['nullable', 'string', 'max:255'],
            'batched_logs.*.apps.*.duration_minutes' => ['required', 'integer', 'min:0'],
            'batched_logs.*.apps.*.opened_count' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'batched_logs.required' => 'Batched logs are required.',
            'batched_logs.*.timeframe.started_at.required' => 'Start time is required for each log timeframe.',
            'batched_logs.*.timeframe.ended_at.required' => 'End time is required for each log timeframe.',
            'batched_logs.*.apps.*.app_name.required' => 'App name is required for each logged app.',
            'batched_logs.*.apps.*.duration_minutes.required' => 'Duration in minutes is required.',
        ];
    }
}