<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\RegisterUseCase;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Api\Auth\MessageResource;
use App\Http\Responders\Api\Auth\MessageResponder;

class RegisterAction
{
    public function __construct(
        private RegisterUseCase $useCase,
        private MessageResponder $responder
    ) {}

    public function __invoke(RegisterRequest $request): MessageResource
    {
        $authEntity = ($this->useCase)($request);

        return ($this->responder)([
            'code' => 'success',
            'message' => $authEntity->message
        ]);
    }
}