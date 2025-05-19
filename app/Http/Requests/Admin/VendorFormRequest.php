<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\AdminRoles;
use App\Enums\ApproveStatus;
use App\Enums\ShopStatus;
use App\Enums\Status;

class VendorFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $vendorId = $this->route('vendor') ? $this->route('vendor') : null;
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('vendors', 'email')->ignore($vendorId),
            ],
            'password' => Rule::requiredIf($vendorId==null),
            'contact_number' => [
                'required',
                'integer',
                Rule::unique('vendors', 'contact_number')->ignore($vendorId),
            ],
            'shop_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'pincode' => 'nullable|digits_between:4,10',
            'status' => Rule::in(Status::values()),
            'is_approved'=>Rule::in(ApproveStatus::values()),
            'is_shop'=>Rule::in(ShopStatus::values()),
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'packaging_processing_charges' => 'numeric',
            'image' => 'nullable|max:2048',
            
        ];
    }

}
