<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
//        if (empty($request->header('authorization'))) {
//            throw new ApiException("Not authorized", 401);
//        }
//
//        if ($request->header('authorization') != config('global.apiKey')) {
//            throw new ApiException("Not authorized", 401);
//        }
        return $next($request);
    }
}
