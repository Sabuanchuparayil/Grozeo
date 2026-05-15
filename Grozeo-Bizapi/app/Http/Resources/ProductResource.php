<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'price' => (float) $this->price,
            'sale_price' => $this->sale_price ? (float) $this->sale_price : null,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand_name,
            'stock' => (int) ($this->stock ?? 0),
            'in_stock' => (bool) ($this->in_stock ?? true),
            'images' => $this->images,
            'thumbnail' => $this->thumbnail,
            'weight' => $this->weight,
            'unit' => $this->unit,
            'storegroup_id' => $this->storegroup_id,
            'is_active' => (bool) $this->is_active,
        ];
    }
}
