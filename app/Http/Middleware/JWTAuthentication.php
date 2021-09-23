<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            //code...
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            //throw $th;
            if ($e instanceof TokenExpiredException) {
                # code...
                $newToken = JWTAuth::parseToken()->refresh();
                return response()->json(
                    [
                        'status' => 0,
                        'message' => 'Token Expired',
                        'token' => $newToken
                    ], 401);
            }else if ($e instanceof TokenInvalidException){
                return response()->json(
                    [
                        'status' => 0,
                        'message' => 'Token Invalid',
                    ], 401);
            }else{
                return response()->json(
                    [
                        'status' => 0,
                        'message' => 'Token Not Found',
                    ], 401);
            }
        }

        return $next($request);
    }
}
