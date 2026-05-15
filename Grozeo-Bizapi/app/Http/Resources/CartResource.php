<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'quantity' => (int) $this->quantity,
            'price' => (float) $this->price,
            'total' => (float) ($this->price * $this->quantity),
            'variant' => $this->variant,
            'image' => $this->image,
            'storegroup_id' => $this->storegroup_id,
        ];
    }
}
