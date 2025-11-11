<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Actions\Api\Auth\{
    RegisterAction, LoginAction, LogoutAction, MeAction,
    RefreshAction, CheckAuthAction, ForgotPasswordAction,
    VerifyOtpAction, ResetPasswordAction, UpdateProfileAction, UpdatePasswordAction
};
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\OTPPasswordRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterAction $action, RegisterRequest $request) { 
        return $action($request); 
    }

    public function login(LoginAction $action, LoginRequest $request) { 
        return $action($request); 
    }

    public function logout(LogoutAction $action, Request $request) { 
        return $action($request); 
    }

    public function me(MeAction $action) { 
        return $action(); 
    }

    public function refresh(RefreshAction $action) { 
        return $action(); 
    }

    public function checkAuth(CheckAuthAction $action, Request $request) { 
        return $action($request); 
    }

    public function forgotPassword(ForgotPasswordAction $action, ForgotPasswordRequest $request) { 
        return $action($request); 
    }
    public function verifyOtp(VerifyOtpAction $action, OTPPasswordRequest $request) { 
        return $action($request); 
    }

    public function resetPassword(ResetPasswordAction $action, ResetPasswordRequest $request) { 
        return $action($request); 
    }

    public function updateProfile(UpdateProfileAction $action, UpdateProfileRequest $request) { 
        return $action($request); 
    }

    public function updatePassword(UpdatePasswordAction $action, UpdatePasswordRequest $request) { 
        return $action($request); 
    }
}