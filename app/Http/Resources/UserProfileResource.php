<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'country_id' => $this->country_id,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'set_your_goal' => $this->weekly_goal_minutes ? (int) ($this->weekly_goal_minutes / 60) : null,
            'onboarding_done' => $this->onboarding_completed,
        ];
    }
}
