<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => ['required', 'digits:10', 'regex:/^[6-9]\d{9}$/', 'unique:users,phone'],
            'type' => ['required', 'in:1,2,3'],
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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 422,
            'status' => false,
            'message' => 'Validation Error',
            'errors' => $validator->errors(),
            'data' => []
        ], 422));
    }
}
