<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginVerifyRequest;
use App\Repositories\Contracts\AuthInterface;

class LoginVerifyController extends Controller
{
    /**
     * @var AuthInterface
     */
    private $login;

    /**
     * @param  AuthInterface  $login
     */
    public function __construct(AuthInterface $login)
    {
        $this->login = $login;
    }

    public function __invoke(LoginVerifyRequest $request)
    {
        $result = $this->login->login_confirmation($request);
        return $this->response($result['code'], $result['status'], $result['data'], $result['message']);
    }
}
