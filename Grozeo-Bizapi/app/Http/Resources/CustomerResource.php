<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'storegroup_id' => $this->storegroup_id,
            'is_active' => (bool) $this->is_active,
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
