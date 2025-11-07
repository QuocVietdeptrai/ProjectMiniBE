<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CloudinaryHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Helpers\MailHelper;
use App\Helpers\RandomHelper;

class AuthController extends Controller
{
    //Đăng ký tài khoản người dùng
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'in:admin,product_manager,order_manager,student_manager,user'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role ?? 'user',
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'code' => 'success',
                'message' => 'Đăng ký thành công',
                // 'user' => $user,
                // 'access_token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //Đăng nhập
    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Kiểm tra email có tồn tại
        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return response()->json([
                'code' => 'error',
                'message' => 'Email không tồn tại!'
            ], 404);
        }

        // Kiểm tra mật khẩu
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'code' => 'error',
                'message' => 'Mật khẩu không đúng!'
            ], 401);
        }

        // Tạo token JWT
        $token = JWTAuth::fromUser($user);

        // Lưu token vào cookie
        $cookie = cookie(
            'access_token',      // tên cookie
            $token,              // giá trị token
            60 * 24,             // thời gian sống (phút), 1 ngày
            '/',                 // path
            'localhost',         // domain
            false,               // secure
            true,                // httpOnly
            false,               // raw
            'lax'                // sameSite
        );

        return response()->json([
            'code' => 'success',
            'access_token' => $token,
            'user' => $user,
        ])->cookie($cookie);
    }



    //Lấy thông tin cá nhân
    public function me(){
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


    public function logout(Request $request){
        $token = $request->cookie('access_token'); //Lấy token từ cookie
        info('Cookie token: ' . $token);

        if ($token) {
            JWTAuth::setToken($token)->invalidate();
        } else {
            return response()->json(['code' => 'error', 'message' => 'Token not found'], 401);
        }

        $cookie = Cookie::forget('access_token');//Xóa token

        return response()->json([
            'code' => 'success',
            'message' => 'Logged out'
        ])->withCookie($cookie);
    }


    public function refresh(){
        $token = JWTAuth::refresh();
        return response()->json(['access_token' => $token]);
    }
    
    public function checkAuth(Request $request){
        $token = $request->cookie('access_token');

        if (!$token) {
            return response()->json([
                'code' => 'error',
                'message' => 'No token found'
            ], 401);
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                return response()->json([
                    'code' => 'error',
                    'message' => 'User not found'
                ], 401);
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
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                'code' => 'error',
                'message' => 'Token expired'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }
    }
    public function forgotpassword(Request $request){
        $request->validate([
                'email' => 'required|email'
        ]);

        //Lấy email từ User
        $user = User::where('email', $request->email)->first();

        //Kiểm tra xem mail đó tồn tại không
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
                        'user' => $user,
                        'code' => 'success'
                ]);
        } else {
                return response()->json(['message' => 'Không thể gửi mail, vui lòng thử lại sau!'], 500);
        }
    }

    //Lấy otp
    public function otp_password(Request $request){
		$request->validate([
				'otp' => 'required|string'
		]);

		$user = User::where('otp', $request->otp)->first();

		if (!$user) {
				return response()->json(['message' => 'OTP không tồn tại!'], 404);
		}
		return response()->json([
				'user' => $user,
                'code' => 'success'
		]);
	}

    //Lấy lại mật khẩu
    public function reset_password(Request $request){
        $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
                return response()->json(['message' => 'Email không tồn tại!'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null; // Xóa OTP sau khi đổi mật khẩu thành công
        $user->save();

        return response()->json([
                'user' => $user,
                'code' => 'success'
        ]);
	}

    //Chỉnh sửa profile
    public function update_profile(Request $request){
        // Lấy user hiện tại từ JWT
        $user = JWTAuth::user();
        if (!$user) {
            return response()->json([
                'code' => 'error',
                'message' => 'Không tìm thấy user'
            ], 401);
        }

        // Validate dữ liệu gửi lên
        $request->validate([
            'name'    => 'sometimes|string|max:255',
            'phone'   => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
            'avatar'  => 'sometimes|file|mimes:jpg,jpeg,png|max:5120', // 5MB
        ]);

        try {
            // Upload avatar nếu có
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $url = CloudinaryHelper::upload($file, 'avatars');
                $user->image = $url;
            }

            if ($request->filled('name')) {
                $user->name = $request->name;
            }
            if ($request->filled('phone')) {
                $user->phone = $request->phone;
            }
            if ($request->filled('address')) {
                $user->address = $request->address;
            }

            $user->save();

            return response()->json([
                'code' => 'success',
                'user' => [
                    'id'      => $user->id,
                    'name'    => $user->name,
                    'email'   => $user->email,
                    'role'    => $user->role,
                    'phone'   => $user->phone,
                    'address' => $user->address,
                    'image'   => $user->image,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 'error',
                'message' => 'Cập nhật thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update_password(Request $request){
        $user = JWTAuth::user();
        if (!$user) {
            return response()->json([
                'code' => 'error',
                'message' => 'Không tìm thấy user'
            ], 401);
        }

        // Validate dữ liệu từ frontend
        $request->validate([
            'password' => 'required|string|min:6|confirmed', // confirmed sẽ check password_confirmation
        ]);

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'code' => 'success',
            'message' => 'Đổi mật khẩu thành công',
        ]);
    }

}
