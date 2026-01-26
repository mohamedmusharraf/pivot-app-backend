<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ActivityResource;

class HobbyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'icon' => $this->icon_url,
            'activities' => ActivityResource::collection($this->whenLoaded('activities')),
        ];
    }
}
