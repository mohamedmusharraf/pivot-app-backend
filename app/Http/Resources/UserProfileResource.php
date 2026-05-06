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
            'country' => $this->country,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'set_your_goal' => $this->set_your_goal,
            'onboarding_done' => $this->onboarding_completed,
        ];
    }
}
