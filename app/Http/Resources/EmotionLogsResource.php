<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmotionLogsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "message" => "Emotion log created successfully.",
            'data' => [ 
                'id' => $this->id,
                'user_id' => $this->user_id,
                'emotion' => $this->emotion,
                'app_name' => $this->app_name,
                'logged_at' => $this->logged_at,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]
        ];
    }
}
