<?php


namespace app\Repositories\Contracts;

use Illuminate\Http\Request;

interface RegisterInterface
{
    public function register(Request $request);
}
