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
            'country'           => $this->country,
            'gender'               => $this->gender,
            'date_of_birth'         => $this->date_of_birth,
            'screen_goal_minutes' => $this->formatScreenGoal((int) $this->screen_goal_minutes),
            'onboarding_done'   => $this->onboarding_completed,
        ];
    }

    private function formatScreenGoal(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;
        return sprintf('%d : %02d', $hours, $remainingMinutes);
    }
}
