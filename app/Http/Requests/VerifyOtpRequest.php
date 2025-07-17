<?php

namespace App\Http\Requests;

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
        return [
            'type' => 'required|in:register,login,forget_password',
            'otp' => 'required|digits:6',
            'phone' => 'required|numeric|digits:10',
        ];

    }
}
