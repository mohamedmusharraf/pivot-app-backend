<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Support\GroupChallengeStatus;

class UpdateUserStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:' . implode(',', [
                GroupChallengeStatus::GROUP_CHALLENGE_STATUS_NOT_READY,
                GroupChallengeStatus::GROUP_CHALLENGE_STATUS_READY,
                GroupChallengeStatus::GROUP_CHALLENGE_STATUS_IN_CHALLENGE,
            ]),
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of: not_ready, ready, in_challenge.',
        ];
    }
}
