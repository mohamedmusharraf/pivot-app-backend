<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StreakLogsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "message" => "Streak Log created successfully.",
            "data" => [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'current_streak' => $this->current_streak,
                'longest_streak' => $this->longest_streak,
                'last_completed_date' => $this->last_completed_date,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ]
        ];
    }
}
