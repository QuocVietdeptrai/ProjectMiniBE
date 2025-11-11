<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\RefreshTokenUseCase;
use App\Http\Resources\Api\Auth\TokenResource;
use App\Http\Responders\Api\Auth\TokenResponder;

class RefreshAction
{
    public function __construct(
        private RefreshTokenUseCase $useCase,
        private TokenResponder $responder
    ) {}

    public function __invoke(): TokenResource
    {
        $authEntity = ($this->useCase)();
        return ($this->responder)($authEntity);
    }
}