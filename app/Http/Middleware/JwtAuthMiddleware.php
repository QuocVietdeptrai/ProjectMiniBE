<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // LẤY TOKEN
        $token = $request->cookie('access_token');

        if (!$token && $request->hasHeader('Authorization')) {
            $authHeader = $request->header('Authorization');
            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                $token = $matches[1];
            }
        }

        try {
            // XÁC THỰC TOKEN
            $user = JWTAuth::setToken($token)->authenticate();

            // === 3. KIỂM TRA TRẠNG THÁI ACTIVE ===
            if ($user->status !== 'active') {
                return response()->json([
                    'error' => 'ACCOUNT_INACTIVE',
                    'message' => 'Tài khoản đã bị vô hiệu hóa.'
                ], 403)
                ->withCookie(cookie()->forget('access_token')); //Xóa token 
            }

            // === 4. GẮN USER VÀO REQUEST ===
            $request->attributes->set('auth_user', $user);
            return $next($request);

        } catch (TokenExpiredException $e) {
            Log::warning('JWT Middleware: Token hết hạn', ['ip' => $request->ip()]);
            return $this->unauthorizedResponse('Token expired');

        } catch (TokenInvalidException $e) {
            Log::warning('JWT Middleware: Token không hợp lệ', ['ip' => $request->ip()]);
            return $this->unauthorizedResponse('Token invalid');

        } catch (\Exception $e) {
            Log::error('JWT Middleware: Lỗi không xác định', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->unauthorizedResponse($e->getMessage());
        }
    }
    private function unauthorizedResponse(string $message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error' => 'UNAUTHORIZED',
            'message' => $message
        ], 401)
        ->withCookie(cookie()->forget('access_token'));
    }
}