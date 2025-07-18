<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class VerifyOtpRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'type' => 'required|in:register,login,forget_password',
            'otp' => 'required|digits:6',
            'phone' => [
                'required',
                'numeric',
                'digits:10',
            ],
        ];

        if ($this->input('type') === 'register') {
            $rules['phone'][] = Rule::unique('users', 'phone');
        }

        return $rules;

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
