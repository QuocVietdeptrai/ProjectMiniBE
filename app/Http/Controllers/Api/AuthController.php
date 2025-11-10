<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CloudinaryHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\OTPPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Helpers\MailHelper;
use App\Helpers\RandomHelper;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthController extends Controller
{
    // Đăng ký tài khoản người dùng
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role ?? 'user',
                'status' => 'inactive',
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'code' => 'success',
                'message' => 'Đăng ký thành công',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Đăng nhập
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return response()->json([
                'code' => 'error',
                'message' => 'Email không tồn tại!'
            ], 404);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'code' => 'error',
                'message' => 'Mật khẩu không đúng!'
            ], 401);
        }

        $token = JWTAuth::fromUser($user);

        $cookie = cookie(
            'access_token', $token, 60 * 24, '/', 'localhost', false, true, false, 'lax'
        );

        return response()->json([
            'code' => 'success',
            'access_token' => $token,
            'user' => $user,
        ])->cookie($cookie);
    }

    // Lấy thông tin cá nhân
    public function me()
    {
        $user = JWTAuth::user();
        return response()->json([
            'code' => 'success',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'phone' => $user->phone,
                'address' => $user->address,
                'image' => $user->image,
            ]
        ]);
    }

    // Đăng xuất
    public function logout()
    {
        $token = request()->cookie('access_token');

        if ($token) {
            JWTAuth::setToken($token)->invalidate();
        } else {
            return response()->json(['code' => 'error', 'message' => 'Token not found'], 401);
        }

        $cookie = Cookie::forget('access_token');

        return response()->json([
            'code' => 'success',
            'message' => 'Logged out'
        ])->withCookie($cookie);
    }

    // Refresh token
    public function refresh()
    {
        $token = JWTAuth::refresh();
        return response()->json(['access_token' => $token]);
    }

    // Check authentication
    public function checkAuth()
    {
        $token = request()->cookie('access_token');

        if (!$token) {
            return response()->json(['code' => 'error', 'message' => 'No token found'], 401);
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                return response()->json(['code' => 'error', 'message' => 'User not found'], 401);
            }

            return response()->json([
                'code' => 'success',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]);
        } catch (TokenExpiredException $e) {
            return response()->json(['code' => 'error', 'message' => 'Token expired'], 401);
        } catch (\Exception $e) {
            return response()->json(['code' => 'error', 'message' => 'Unauthorized'], 401);
        }
    }

    // Quên mật khẩu
    public function forgotpassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email không tồn tại!'], 404);
        }

        $otp = RandomHelper::generateOTP();
        $user->otp = $otp;
        $user->save();

        $subject = "Mã OTP khôi phục mật khẩu";
        $content = "<p>Xin chào <b>{$user->name}</b>,</p>
                    <p>Mã OTP của bạn là: <b>{$otp}</b></p>
                    <p>OTP có hiệu lực trong 5 phút.</p>";

        if (MailHelper::sendMail($user->email, $subject, $content)) {
            return response()->json([
                'message' => 'Đã gửi OTP tới email của bạn!',
                'code' => 'success'
            ]);
        } else {
            return response()->json(['message' => 'Không thể gửi mail, vui lòng thử lại sau!'], 500);
        }
    }

    // Xác nhận OTP
    public function otp_password(OTPPasswordRequest $request)
    {
        $user = User::where('otp', $request->otp)->first();

        if (!$user) {
            return response()->json(['message' => 'OTP không tồn tại!'], 404);
        }

        return response()->json([
            'code' => 'success',
            'message' => 'Xác nhận OTP thành công !'
        ]);
    }

    // Reset mật khẩu
    public function reset_password(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email không tồn tại!'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->save();

        return response()->json([
            'code' => 'success',
            'message' => 'Đặt lại mật khẩu thành công !'
        ]);
    }

    // Cập nhật profile
    public function update_profile(UpdateProfileRequest $request)
    {
        $user = JWTAuth::user();
        if (!$user) {
            return response()->json(['code' => 'error', 'message' => 'Không tìm thấy user'], 401);
        }

        try {
            if ($request->hasFile('avatar')) {
                $url = CloudinaryHelper::upload($request->file('avatar'), 'avatars');
                $user->image = $url;
            }

            if ($request->filled('name')) $user->name = $request->name;
            if ($request->filled('phone')) $user->phone = $request->phone;
            if ($request->filled('address')) $user->address = $request->address;

            $user->save();

            return response()->json([
                'code' => 'success',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'image' => $user->image,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['code' => 'error', 'message' => 'Cập nhật thất bại: ' . $e->getMessage()], 500);
        }
    }

    // Cập nhật mật khẩu
    public function update_password(UpdatePasswordRequest $request)
    {
        $user = JWTAuth::user();
        if (!$user) {
            return response()->json(['code' => 'error', 'message' => 'Không tìm thấy user'], 401);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['code' => 'success', 'message' => 'Đổi mật khẩu thành công']);
    }
}
