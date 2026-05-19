<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tier' => $this->tier ? [
                'id' => $this->tier->id,
                'name' => $this->tier->name,
            ] : null,
            'type' => $this->type,
            'environment' => $this->environment,
            'active' => $this->active,
            'store' => $this->store,
            'product_id' => $this->product_id,
            'started_at' => $this->started_at,
            'expires_at' => $this->expires_at,
        ];
    }
}
