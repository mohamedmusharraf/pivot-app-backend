<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateAppBlockLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'event_type' => ['sometimes', 'string', 'max:255'],
            'app_name' => ['sometimes', 'string', 'max:255'],
            'package_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'blocked_at' => ['sometimes', 'date'],
            'released_at' => ['sometimes', 'nullable', 'date'],
            'attempted' => ['sometimes', 'boolean'],
            'success' => ['sometimes', 'boolean'],
            'time_saved_minutes' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $blockedAt = $this->input('blocked_at');
            $releasedAt = $this->input('released_at');

            if ($blockedAt && $releasedAt && strtotime($releasedAt) < strtotime($blockedAt)) {
                $validator->errors()->add(
                    'released_at',
                    'The released_at date must be after or equal to the blocked_at date.'
                );
            }
        });
    }
}
