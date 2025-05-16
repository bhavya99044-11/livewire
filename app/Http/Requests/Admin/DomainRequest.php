<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class DomainRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        $domainId=$request->route('domain')?$request->route('domain')->id:null;

        return [
            'name' => 'required|string|max:255|unique:domains,name'.$domainId,
            'url' => 'required|string|max:255|unique:domains,url'.$domainId,
        ];
    }
}
