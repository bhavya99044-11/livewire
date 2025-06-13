<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Status;
use App\Enums\ApproveStatus;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'description'   => $this->description,
            'status'        => [
                'value'    => $this->status,
                'label'  => Status::from($this->status)->label(),
                'color'=> Status::from($this->status)->color(),
                'bgColor'=> Status::from($this->status)->bgColor(),
            ],
            'is_approve'    => [
                'value'    => $this->is_approve,
                'label'  => ApproveStatus::from($this->is_approve)->label(),
                'color'=> ApproveStatus::from($this->is_approve)->color(),
                'bgColor'=> ApproveStatus::from($this->is_approve)->bgColor(),
            ],
            'rejection_reason' => $this->rejection_reason,
            'approved_by'   => $this->approve->only(['id', 'name']),
            'vendor'     => $this->vendor->only(['id','name']),
            // Relationships
            'sub_products'  => SubProductResource::collection($this->subProducts)->toArray(request()),
            'items'         => ProductItemResource::collection($this->items)->toArray(request()),
        ];
    }
}
