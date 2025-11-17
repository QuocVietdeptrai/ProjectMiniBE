<?php

namespace App\Http\Middleware;

use App\Enums\StatusCode;
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

            // KIỂM TRA TRẠNG THÁI TÀI KHOẢN
            if ($user->status !== 'active') {
                return response()->json([
                    'error' => 'ACCOUNT_INACTIVE',
                    'message' => 'Tài khoản đã bị vô hiệu hóa.'
                ], StatusCode::FORBIDDEN)
                ->withCookie(cookie()->forget('access_token')); //Xóa token 
            }

            //Gán thông tin user đã xác thực vào request
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
        ], StatusCode::UNAUTHORIZED)
        ->withCookie(cookie()->forget('access_token'));
    }
}