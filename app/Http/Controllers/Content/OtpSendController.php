<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Repositories\Contracts\ContentInterface;
use App\Repositories\Contracts\RegisterInterface;
use Illuminate\Http\Request;

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
        $result = $this->register->SendOTP($request);

        return $this->response($result['code'], $result['status'], $result['data'], $result['message']);
    }
}
