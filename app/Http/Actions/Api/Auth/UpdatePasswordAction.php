<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\UpdatePasswordUseCase;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Resources\Api\Auth\MessageResource;
use App\Http\Responders\Api\Auth\MessageResponder;

class UpdatePasswordAction
{
    public function __construct(
        private UpdatePasswordUseCase $useCase,
        private MessageResponder $responder
    ) {}

    public function __invoke(UpdatePasswordRequest $request): MessageResource
    {
        $result = ($this->useCase)($request->password);
        return ($this->responder)($result);
    }
}