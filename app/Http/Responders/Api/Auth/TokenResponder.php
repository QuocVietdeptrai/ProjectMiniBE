<?php

namespace App\Http\Responders\Api\Auth;

use App\Domain\Auth\Domain\Entity\AuthEntity;
use App\Http\Resources\Api\Auth\TokenResource;

class TokenResponder
{
    public function __invoke(AuthEntity $authEntity): TokenResource
    {
        return new TokenResource($authEntity);
    }
}