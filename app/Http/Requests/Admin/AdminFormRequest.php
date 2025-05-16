<?php

namespace App\Http\Requests\Admin;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    protected $adminId;

    public function authorize(): bool
    {
        return true;
    }

    public function __construct($adminId = null)
    {
        $this->adminId = $adminId;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($this->adminId),
            ],
            'password' => [
                Rule::requiredIf(is_null($this->adminId)),
                'nullable',
                'string',
                'min:8',
            ],
            'status' => ['required', Rule::in(Status::values())],
        ];
    }
}
