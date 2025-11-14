<?php

namespace App\Domain\Auth\Infrastructure;

use App\Domain\Auth\Domain\Service\AuthTokenServiceInterface;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthTokenService implements AuthTokenServiceInterface
{
    public function attempt(array $credentials): ?string
    {
        return JWTAuth::attempt($credentials);
    }
    public function generateToken(User $user): string
    {
        return JWTAuth::fromUser($user);
    }
    public function userFromToken(string $token)
    {
        return JWTAuth::setToken($token)->authenticate();
    }
}
