<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class ChangePasswordRequest extends FormRequest
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
            'current_password' => ['required','min:8','string',
        function ($attribute,$value,$fail){
            if(!Hash::check($value,Auth::guard('admin')->user()->password)){
                $fail('The current password is incorrect.');
            }
           
        }
    
    ],
            'password' => ['required','string','min:6','confirmed',
            function($attribute,$value,$fail){if(Hash::check($value,Auth::guard('admin')->user()->password)){
                $fail('Dont use previous password');
            }
        }
        ],
            'password_confirmation' => 'required|string|min:6',


        ];
    }
}
