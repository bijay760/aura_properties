<?php


namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface AuthInterface
{
    public function login(Request $request);
    public function login_confirmation(Request $request);
}
