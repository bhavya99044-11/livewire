<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\Status;
use App\Enums\AdminRoles;
class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {

        $data= [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'role' => [
                'value' => $this->role,
                'label' => AdminRoles::from($this->role)->label(),
                'color' => AdminRoles::from($this->role)->color(),
                'bgColor' => AdminRoles::from($this->role)->bgColor(),
            ],
            'status'     =>   [
                'value' => $this->status,
                'label' => Status::from($this->status)->label(),
                'color' => Status::from($this->status)->color(),
                'bgColor' => Status::from($this->status)->bgColor(),
            ],

        ];
        return $data;
       
    }

    public function with($request)
    {
        return [
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
            ],
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
            ],
        ];
    }
}
