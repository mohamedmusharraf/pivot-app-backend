<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $dateOfBirth = $this->date_of_birth ? Carbon::parse($this->date_of_birth) : null;
        $goalHours = $this->set_your_goal ?? ($this->weekly_goal_minutes ? (int) ($this->weekly_goal_minutes / 60) : null);
        $goalMinutes = $this->weekly_goal_minutes;

        return [
            'user_name' => $this->user?->name,
            'email' => $this->user?->email,
            'country_name' => $this->country?->name,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'age' => $dateOfBirth?->age,
            'set_your_goal' => $goalHours,
            'Set_your_goal_minutes' => $goalMinutes,
            'category' => $this->hobbies->pluck('name')->values()->all(),
            'onboarding_done' => $this->onboarding_completed,
        ];
    }
}
