<?php

namespace App\Http\Responders\Api\Auth;

use App\Domain\Auth\Domain\Entity\AuthEntity;
use App\Http\Resources\Api\Auth\LoginResource;

class LoginResponder
{
    public function __invoke(AuthEntity $authEntity): LoginResource
    {
        return new LoginResource([
            'access_token' => $authEntity->token,
            'user' => $authEntity->user
        ]);
    }
}