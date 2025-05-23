<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductStepTwoRequest extends FormRequest
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
      
            return [
                'sub_products.*.id' => 'nullable|integer|exists:sub_products,id',
                'sub_products.*.size_type' => 'required|string|max:50',
                'sub_products.*.size' => 'required|string|max:50',
                'sub_products.*.price' => 'required|numeric|min:0',
                'sub_products.*.base_price' => 'required|numeric|min:0',
                'sub_products.*.quantity' => 'required|integer|min:0',
                'sub_products.*.status' => 'required|in:' . implode(',', array_column(\App\Enums\Status::cases(), 'value')),
                'sub_products.*.specifications.*.name' => 'required|string|max:100',
                'sub_products.*.specifications.*.value' => 'required|string|max:100',
                'sub_products.*.images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'sub_products.*.existing_images.*' => 'nullable|string',
            ];
        }
    
}
