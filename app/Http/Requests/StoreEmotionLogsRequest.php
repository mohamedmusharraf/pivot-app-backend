<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmotionLogsRequest extends FormRequest
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
            'events' => ['required', 'array', 'min:1'],
            'events.*.emotion' => ['required', 'string'],
            'events.*.app_name' => ['nullable', 'string'],
            'events.*.logged_at' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'events.required' => 'At least one emotion log event is required.',
            'events.*.emotion.required' => 'The emotion field is required for each event.',
            'events.*.logged_at.required' => 'The logged_at date is required.',
            'events.*.logged_at.date' => 'The logged_at must be a valid date.',
        ];
    }
}