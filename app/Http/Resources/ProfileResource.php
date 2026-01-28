<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'country_id' => $this->country_id,
            'name'       => $this->name,
            'gender'     => $this->gender,
            'age_range'  => $this->age_range,
            'onboarding_complete' => $this->onboarding_complete,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
