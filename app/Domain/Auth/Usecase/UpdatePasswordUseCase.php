<?php

namespace App\Domain\Auth\UseCase;

use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdatePasswordUseCase
{
    public function __invoke(string $password): array
    {
        $user = JWTAuth::user();
        $user->password = Hash::make($password);
        $user->save();

        return ['success' => true, 'message' => 'Đổi mật khẩu thành công'];
    }
}