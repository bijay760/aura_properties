<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Repositories\Contracts\RegisterInterface;

class RegisterController extends Controller
{
    /**
     * @var RegisterInterface
     */
    private $register;

    /**
     * @param  RegisterInterface  $register
     */
    public function __construct(RegisterInterface $register)
    {
        $this->register = $register;
    }

    public function __invoke(RegisterRequest $request)
    {
        $result = $this->register->register($request);

        return $this->response($result['code'], $result['status'], $result['data'], $result['message']);
    }
}
