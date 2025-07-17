<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Repositories\Contracts\AuthInterface;
use App\Repositories\Contracts\loginInterface;

class LoginController extends Controller
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

    public function __invoke(LoginRequest $request)
    {
        $result = $this->login->login($request);
        return $this->response($result['code'], $result['status'], $result['data'], $result['message']);
    }
}
