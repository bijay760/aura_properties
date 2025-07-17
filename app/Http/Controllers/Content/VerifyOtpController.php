<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyOtpRequest;
use App\Repositories\Contracts\ContentInterface;
use App\Repositories\Contracts\RegisterInterface;
use Illuminate\Http\Request;

class VerifyOtpController extends Controller
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

    public function __invoke(VerifyOtpRequest $request)
    {
        $result = $this->register->verifyOtp($request);

        return $this->response($result['code'], $result['status'], $result['data'], $result['message']);
    }
}
