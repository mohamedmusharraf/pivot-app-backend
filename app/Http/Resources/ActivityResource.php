<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'title'    => $this->title,
            'duration' => $this->duration_minutes,
            'energy'   => $this->energy_level,
            'age_suitability' => $this->age_suitability,
            'hobby'    => [
                'id'   => $this->hobby->id,
                'name' => $this->hobby->name,
            ],
        ];
    }
}
