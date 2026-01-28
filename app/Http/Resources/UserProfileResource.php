<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_id'              => $this->user_id,
            'country_id'           => $this->country_id,
            'gender'               => $this->gender,
            'age_range'         => $this->age_range,
            'screen_goal_hours' => $this->screen_goal_hours,
            'onboarding_done'   => $this->onboarding_completed,
        ];
    }
}
