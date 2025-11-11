<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Domain\Entity\AuthEntity;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Domain\Auth\Exception\AuthenticationException;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(LoginRequest $request): AuthEntity
    {
        $user = $this->userRepository->findByEmail($request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new AuthenticationException('Email hoặc mật khẩu không đúng!');
        }

        $token = JWTAuth::fromUser($user);
        $this->userRepository->saveLastLogin($user);

        return new AuthEntity(
            token: $token,
            user: $this->userRepository->toEntity($user)
        );
    }
}