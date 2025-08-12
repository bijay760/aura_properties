<?php

namespace App\Http\Middleware;

use App\Exceptions\JwtException;
use Closure;
use Illuminate\Http\Request;
use Exception;
use App\Helpers\Auth;
use Illuminate\Support\Facades\Log;

class ApiAuthenticate
{
    private $jwt;

    public function __construct(Auth $jwt)
    {
        $this->jwt = $jwt;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (empty($request->header('authentication'))) {
            throw new JwtException("Invalid or missing auth token", 401);
        }
        try {
            $data = [
                'session_id' => $this->jwt->getData(),
                'ip_address' => get_ip_address()
            ];

            $params = [
                'json' => json_encode($data)
            ];

            $sql = 'SELECT `USER_SESSION`(:json) AS result';
            $user_session = wdb($sql, $params);
            Log::info("Sessionlog".print_r($user_session, true));

            if ($user_session['status'] == false) {
                throw new JwtException("Token invalid or expired", 401);
            }  if ($user_session['data'] == null) {
                throw new JwtException("Token invalid or expired", 401);
            }

            return $next($request);
        } catch (Exception $e) {
            throw new JwtException("Token invalid or expired", 401);
        }
    }
}
