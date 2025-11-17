<?php

namespace App\Domain\Auth\Usecase;

use App\Domain\Auth\Domain\Entity\AuthEntity;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Domain\Auth\Exception\AuthenticationException;
use App\Http\Requests\Auth\LoginRequest;
use App\Domain\Auth\Domain\Service\AuthTokenServiceInterface;
use Illuminate\Support\Facades\Hash;

class LoginUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private AuthTokenServiceInterface $tokenService
    ) {}

    public function __invoke(LoginRequest $request): AuthEntity
    {
        // Lấy user model
        $userModel = $this->userRepository->findModelByEmail($request->email);
        if (!$userModel || !Hash::check($request->password, $userModel->password)) {
            throw new AuthenticationException('Email hoặc mật khẩu không đúng!');
        }
        $token = $this->tokenService->generateToken($userModel);
        $userEntity = $this->userRepository->toEntity($userModel);
        $this->userRepository->saveLastLogin($userEntity);

        return new AuthEntity(
            token: $token,
            user: $userEntity
        );
    }
}
