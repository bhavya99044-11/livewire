<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PermissionRequest extends FormRequest
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
        $id = $this->route('permission');

        return [
            'module' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'slug' => 'string|max:255|unique:permissions,slug,'.$id,
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug(Str::lower($this->input('module')).' '.Str::lower($this->input('name'))),
        ]);
    }

    public function messages()
    {
        return [
            'slug.unique' => 'Use different module or name',
        ];
    }
}
