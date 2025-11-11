<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\UpdateProfileUseCase;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Resources\Api\Auth\UserResource;
use App\Http\Responders\Api\Auth\UserResponder;

class UpdateProfileAction
{
    public function __construct(
        private UpdateProfileUseCase $useCase,
        private UserResponder $responder
    ) {}

    public function __invoke(UpdateProfileRequest $request): UserResource
    {
        $userEntity = ($this->useCase)(
            $request->only('name', 'phone', 'address'),
            $request->file('avatar')
        );

        return ($this->responder)($userEntity);
    }
}