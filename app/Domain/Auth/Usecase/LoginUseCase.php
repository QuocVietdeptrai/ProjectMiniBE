<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Domain\Entity\AuthEntity;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Domain\Auth\Exception\AuthenticationException;
use App\Http\Requests\Auth\LoginRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class LoginUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

public function __invoke(LoginRequest $request): AuthEntity
{
    $userModel = $this->userRepository->findModelByEmail($request->email);

    if (!$userModel || !Hash::check($request->password, $userModel->password)) {
        throw new AuthenticationException('Email hoặc mật khẩu không đúng!');
    }

    $token = $this->userRepository->generateToken($userModel);
    $userEntity = $this->userRepository->toEntity($userModel);
    $this->userRepository->saveLastLogin($userEntity);

    return new AuthEntity(
        token: $token,
        user: $userEntity
    );
}

}
