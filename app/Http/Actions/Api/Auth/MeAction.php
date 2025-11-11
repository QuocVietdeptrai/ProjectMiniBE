<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\MeUseCase;
use App\Http\Resources\Api\Auth\UserResource;
use App\Http\Responders\Api\Auth\UserResponder;

class MeAction
{
    public function __construct(
        private MeUseCase $useCase,
        private UserResponder $responder
    ) {}

    public function __invoke(): UserResource
    {
        $userEntity = ($this->useCase)();
        return ($this->responder)($userEntity);
    }
}