<?php

namespace App\Http\Actions\Api\Auth;

use App\Domain\Auth\UseCase\UpdateProfileUseCase;
use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Resources\Api\Auth\UserResource;
use App\Http\Responders\Api\Auth\UserResponder;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateProfileAction
{
    public function __construct(
        private UpdateProfileUseCase $useCase,
        private UserResponder $responder
    ) {
    }

    public function __invoke(UpdateProfileRequest $request): UserResource
    {
        // Lấy user hiện tại để lấy id, email, role
        $currentUser = JWTAuth::user();

        // Tạo entity từ request + thông tin hiện tại
        $userEntity = new UserEntity(
            id: $currentUser->id,
            name: $request->input('name', $currentUser->name),
            email: $currentUser->email,
            role: $currentUser->role,
            phone: $request->input('phone', $currentUser->phone),
            address: $request->input('address', $currentUser->address),
            image: $currentUser->image,
            created_at: $currentUser->created_at,
            last_login_at: $currentUser->last_login_at
        );

        // Gọi use case
        $updatedEntity = ($this->useCase)($userEntity, $request->file('avatar'));

        // Trả về resource
        return ($this->responder)($updatedEntity);
    }
}
