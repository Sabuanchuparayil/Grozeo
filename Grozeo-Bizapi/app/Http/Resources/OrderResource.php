<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'total' => (float) $this->total,
            'subtotal' => (float) $this->subtotal,
            'delivery_charge' => (float) ($this->delivery_charge ?? 0),
            'discount' => (float) ($this->discount ?? 0),
            'tax' => (float) ($this->tax ?? 0),
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'delivery_type' => $this->delivery_type,
            'customer_id' => $this->customer_id,
            'storegroup_id' => $this->storegroup_id,
            'branch_id' => $this->branch_id,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'address' => new AddressResource($this->whenLoaded('address')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
