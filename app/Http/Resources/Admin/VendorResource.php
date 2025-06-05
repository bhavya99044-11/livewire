<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Status; // assuming you have this enum similar to your Admin example
use App\Enums\ShopStatus;
use App\Enums\ApproveStatus;
class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                          => $this->id,
            'domain_ids'                  => $this->domains()->pluck('id'), // or map domain info if needed
            'name'                        => $this->name,
            'email'                       => $this->email,
            'contact_number'              => $this->contact_number,
            'shop_name'                   => $this->shop_name,
            'image'                       => $this->image,
            'logo_url'                    => $this->logo_url,
            'address'                     => $this->address,
            'city'                        => $this->city,
            'state'                       => $this->state,
            'country'                     => $this->country,
            'pincode'                     => $this->pincode,
            'status'                      => [
                'value' => $this->status,
                'label' => Status::from($this->status)->label(),    
                'color' => Status::from($this->status)->color(),
                'bgColor' => Status::from($this->status)->bgColor(),
            ],
            'is_shop'                    => 
            [
                'value' => $this->is_shop,
                'label' => ShopStatus::from($this->is_shop)->label(),
            ],
            'latitude'                    => $this->latitude,
            'longitude'                   => $this->longitude,
            'open_time'                   => $this->open_time?->format('H:i:s'),  
            'close_time'                  => $this->close_time?->format('H:i:s'),
            'packaging_processing_charges'=> (float) $this->packaging_processing_charges,
            'is_approved'                 => [
                'value' => $this->is_approved,
                'label' => ApproveStatus::from($this->is_approved)->label(),
                'color' => ApproveStatus::from($this->is_approved)->color(),
            ],
           'domains'=> $this->domains->map(function($domain) {
                return [
                    'id' => $domain->id,
                    'name' => $domain->name,
                ];
            }),

        ];
    }

  
}
