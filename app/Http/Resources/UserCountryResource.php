<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCountryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'iso_code' => $this->iso_code,
            'country' => $this->name,
            'police' => $this->formatEmergencyContacts($this->police, 'police'),
            'ambulance' => $this->formatEmergencyContacts($this->ambulance, 'ambulance'),
            'fire' => $this->formatEmergencyContacts($this->fire, 'fire'),
        ];
    }

    protected function formatEmergencyContacts(?string $value, string $prefix): array
    {
        if (trim((string) $value) === '') {
            return [];
        }

        $contacts = array_filter(array_map('trim', explode(',', $value)), fn($item) => $item !== '');

        $formatted = [];

        foreach ($contacts as $index => $contact) {
            $formatted["{$prefix}_" . ($index + 1)] = $contact;
        }

        return $formatted;
    }
}
