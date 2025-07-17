<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => ['required', 'digits:10', 'regex:/^[6-9]\d{9}$/', 'unique:users,phone'],
            'type' => ['required', 'in:1,2,3'], // assuming type 1 = normal, 2 = business (adjust if needed)
            'full_name' => ['required', 'string', 'min:4', 'max:64'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'confirm_password' => ['required', 'same:password'],
            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],
            'token' => ['required', 'string'],
        ];
    }
}
