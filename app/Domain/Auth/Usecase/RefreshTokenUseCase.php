<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Domain\Entity\AuthEntity;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshTokenUseCase
{
    public function __invoke(): AuthEntity
    {
        $newToken = JWTAuth::refresh();

        return new AuthEntity(
            token: $newToken,
            message: 'Token refreshed successfully'
        );
    }
}