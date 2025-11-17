<?php

namespace App\Domain\Auth\Infrastructure;

use App\Domain\Auth\Domain\Service\AuthTokenServiceInterface;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthTokenService implements AuthTokenServiceInterface
{
    //Lấy token từ thông tin đăng nhập
    public function attempt(array $credentials): ?string
    {
        return JWTAuth::attempt($credentials);
    }
    //Tạo token từ user
    public function generateToken(User $user): string
    {
        return JWTAuth::fromUser($user);
    }
    //Lấy user từ token
    public function userFromToken(string $token)
    {
        return JWTAuth::setToken($token)->authenticate();
    }
    //Lấy token từ header request
    public function user(): ?User
    {
        return JWTAuth::user();
    }
}
