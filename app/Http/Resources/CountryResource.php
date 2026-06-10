<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'iso_code' => $this->iso_code,
            'name' => $this->name,
            'default_locale' => $this->default_locale,
            'currency_code' => $this->currency_code,
            'phone_code' => $this->phone_code,
            'timezone' => $this->timezone,
            'is_active' => (bool) $this->is_active,
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}
