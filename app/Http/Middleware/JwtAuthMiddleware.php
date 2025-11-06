<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthMiddleware
{public function handle(Request $request, Closure $next)
{
    Log::info('[JwtAuthMiddleware] Middleware chạy.');

    // Lấy token từ cookie hoặc header
    $token = $request->cookie('access_token');
    if (!$token && $request->hasHeader('Authorization')) {
        $authHeader = $request->header('Authorization');
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
    }

    if (!$token) {
        return response()->json(['error' => 'Unauthorized - No token'], 401);
    }

    try {
        $user = JWTAuth::setToken($token)->authenticate();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized - No user'], 401);
        }

        // **Gắn user vào request**
        $request->merge(['user' => $user]);  // hoặc dùng $request->attributes->set('user', $user)

    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        return response()->json(['error' => 'Token expired'], 401);
    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        return response()->json(['error' => 'Token invalid'], 401);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Unauthorized - ' . $e->getMessage()], 401);
    }

    return $next($request);
}

}