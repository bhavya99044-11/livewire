<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'product_id' => $this->product_id,
            'size_type'  => $this->size_type,
            'size'       => $this->size,
            'sku'        => $this->sku,
            'price'      => (float) $this->price,
            'base_price' => (float) $this->base_price,
            'quantity'   => (int) $this->quantity,
            'status'     => (bool) $this->status,

            // Relationships
            'specifications' => ProductSpecificationResource::collection($this->specifications)->toArray(request()),
            'images'         => ProductImageResource::collection($this->images)->toArray(request()),
        ];
    }
}
