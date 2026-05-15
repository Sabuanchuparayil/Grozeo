<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'state' => $this->state,
            'pincode' => $this->pincode,
            'landmark' => $this->landmark,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_default' => (bool) ($this->is_default ?? false),
            'address_type' => $this->address_type,
        ];
    }
}
