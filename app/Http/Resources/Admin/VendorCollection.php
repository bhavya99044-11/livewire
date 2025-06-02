<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VendorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'data' => VendorResource::collection($this->collection),
        'meta' => [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
        ],
        'enumApproveStatus' => \App\Enums\ApproveStatus::toJsObject(),
        'enumStatus' => \App\Enums\Status::toJsObject(),
        'enumShopStatus' => \App\Enums\ShopStatus::toJsObject(),
        ];
    }

    public function with($request)
    {
        return [
            'enumApproveStatus' => \App\Enums\ApproveStatus::toJsObject(),
            'enumStatus' => \App\Enums\Status::toJsObject(),
            'enumShopStatus' => \App\Enums\ShopStatus::toJsObject(),
        ];
    }

}
