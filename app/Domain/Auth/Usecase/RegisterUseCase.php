<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Domain\Entity\AuthEntity;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Domain\Auth\Exception\AuthenticationException;
use App\Http\Requests\Auth\RegisterRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(RegisterRequest $request): AuthEntity
    {
        $user = $this->userRepository->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role ?? 'user',
            'status' => 'inactive',
        ]);

        $token = JWTAuth::fromUser($user);

        return new AuthEntity(
            token: $token,
            message: 'Đăng ký thành công'
        );
    }
}