<?php

namespace App\Http\Responders\Api\Auth;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Http\Resources\Api\Auth\UserResource;

class UserResponder
{
    public function __invoke(UserEntity $userEntity): UserResource
    {
        return new UserResource($userEntity);
    }
}