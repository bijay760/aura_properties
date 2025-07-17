<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class LoginVerifyRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type'=>'required|in:login,register',
            'otp'=>'required|digits:6',
            'identifier' => [
                'required',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('users')
                        ->where('email', $value)
                        ->orWhere('phone', $value)
                        ->exists();

                    if (!$exists) {
                        $fail('The identifier must match an existing email or phone number.');
                    }
                },
            ],
        ];
    }
}
