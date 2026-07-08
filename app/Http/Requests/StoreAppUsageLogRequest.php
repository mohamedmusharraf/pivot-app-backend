<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppUsageLogRequest extends FormRequest
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
            'package_name' => ['nullable', 'string', 'max:255'],
            'started_at' => ['required', 'date_format:Y-m-d H:i:s'],
            'ended_at' => ['required', 'date_format:Y-m-d H:i:s', 'after:started_at'],
            'usage_minutes' => ['nullable', 'integer', 'min:1'],
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
            'started_at.required' => 'The started at time is required.',
            'started_at.date_format' => 'The started at must be in format: Y-m-d H:i:s',
            'ended_at.required' => 'The ended at time is required.',
            'ended_at.date_format' => 'The ended at must be in format: Y-m-d H:i:s',
            'ended_at.after' => 'The ended at must be after started at.',
            'usage_minutes.min' => 'Usage minutes must be at least 1.',
        ];
    }
}
