<?php

namespace App\Http\Middleware;

use Closure;
use Exception; // WAJIB ADA: Tanpa ini, catch (Exception $e) akan bikin Error 500
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Cek apakah token valid dan ambil data user
            $user = JWTAuth::parseToken()->authenticate();
            
            if (!$user) {
                return response()->json(['status' => 'User not found'], 404);
            }
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(['status' => 'Token is Invalid'], 401);
            } else if ($e instanceof TokenExpiredException) {
                return response()->json(['status' => 'Token is Expired'], 401);
            } else {
                return response()->json(['status' => 'Authorization Token not found'], 401);
            }
        }
        return $next($request);
    }
}