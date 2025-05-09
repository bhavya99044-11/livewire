<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ActionRequest extends FormRequest
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function rules(): array
    {
        return [
            'values' => ['required', 'array', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'values.required' => 'You must provide some values.',
            'values.array' => 'The values must be an array.',
            'values.min' => 'At least one value must be selected.',
            'values.*.required' => 'Empty items are not allowed.',
        ];
    }

    public function validate()
    {
        return validator($this->data, $this->rules(), $this->messages())->validate();
    }
}
