<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Repositories\Contracts\ContentInterface;
use App\Repositories\Contracts\RegisterInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OtpSendController extends Controller
{
    /**
     * @var ContentInterface
     */
    private $register;

    /**
     * @param  ContentInterface  $register
     */
    public function __construct(ContentInterface $register)
    {
        $this->register = $register;
    }

    public function __invoke(Request $request)
    {

        $rules = [
            'type' => ['required', 'string', Rule::in(['register', 'login', 'forget_password'])],
            'phone' => ['required', 'string', 'digits:10'],
        ];

        if ($request->input('type') === 'register') {
            $rules['phone'][] = Rule::unique('users', 'phone');
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return [
                'code' => 422,
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'data' => []
            ];
        }
        $result = $this->register->SendOTP($request);

        return $this->response($result['code'], $result['status'], $result['data'], $result['message']);
    }
}
