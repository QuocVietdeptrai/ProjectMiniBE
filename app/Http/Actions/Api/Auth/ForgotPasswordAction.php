<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\ForgotPasswordUseCase;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Resources\Api\Auth\MessageResource;
use App\Http\Responders\Api\Auth\MessageResponder;

class ForgotPasswordAction
{
    public function __construct(
        private ForgotPasswordUseCase $useCase,
        private MessageResponder $responder
    ) {}

    public function __invoke(ForgotPasswordRequest $request): MessageResource
    {
        $result = ($this->useCase)($request->email);
        return ($this->responder)($result);
    }
}