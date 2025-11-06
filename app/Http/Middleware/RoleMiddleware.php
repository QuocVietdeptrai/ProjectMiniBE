<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        Log::info('[RoleMiddleware] Bắt đầu kiểm tra quyền...');
        Log::info('Danh sách role được phép:', $roles);

        try {
            // Lấy token từ cookie
            $token = $request->cookie('access_token');

            // Nếu không có cookie, thử lấy từ header Authorization
            if (!$token && $request->hasHeader('Authorization')) {
                $authHeader = $request->header('Authorization'); // dạng "Bearer <token>"
                if (preg_match('/Bearer\s+(\S+)/i', $authHeader, $matches)) {
                    $token = $matches[1];
                }
            }

            Log::info('Token nhận được:', [$token]);

            if (!$token) {
                return response()->json(['error' => 'Thiếu token, vui lòng đăng nhập'], 401);
            }

            // Xác thực token
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                Log::warning('Không xác thực được user từ token!');
                return response()->json(['error' => 'Người dùng không tồn tại hoặc token không hợp lệ'], 401);
            }

            Log::info('Token hợp lệ, user:', [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
            ]);

            Auth::setUser($user);

            // Kiểm tra quyền
            if (!in_array($user->role, $roles)) {
                Log::warning("Quyền không hợp lệ. User role: {$user->role}, yêu cầu: " . implode(', ', $roles));
                return response()->json([
                    'error' => 'Bạn không có quyền truy cập chức năng này'
                ], 403);
            }

            Log::info('Người dùng có quyền hợp lệ, cho phép đi tiếp.');
            return $next($request);

        } catch (\Exception $e) {
            Log::error('Lỗi xác thực trong RoleMiddleware:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Lỗi xác thực, vui lòng đăng nhập lại',
                'message' => $e->getMessage()
            ], 401);
        }
    }
}