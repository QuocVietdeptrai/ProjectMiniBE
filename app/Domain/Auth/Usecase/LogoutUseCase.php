<?php

namespace App\Domain\Auth\UseCase;

use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutUseCase
{
    public function __invoke(string $token): void
    {
        JWTAuth::setToken($token)->invalidate();
    }
}