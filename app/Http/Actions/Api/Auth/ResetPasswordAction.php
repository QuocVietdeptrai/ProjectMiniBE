<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\ResetPasswordUseCase;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\Api\Auth\MessageResource;
use App\Http\Responders\Api\Auth\MessageResponder;

class ResetPasswordAction
{
    public function __construct(
        private ResetPasswordUseCase $useCase,
        private MessageResponder $responder
    ) {}

    public function __invoke(ResetPasswordRequest $request): MessageResource
    {
        $result = ($this->useCase)($request->email, $request->password);
        return ($this->responder)($result);
    }
}