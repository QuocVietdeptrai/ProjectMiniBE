<?php

namespace App\Domain\Auth\Usecase;

use App\Domain\Auth\Domain\Entity\AuthEntity;
use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(RegisterRequest $request): AuthEntity
    {
        $userEntity = new UserEntity(
            id: null, 
            name: $request->name,
            email: $request->email,
            password: bcrypt($request->password),
            role: $request->role ?? 'user',
            status: 'inactive',
            phone: $request->phone ?? null,
            address: $request->address ?? null,
            image: $request->image ?? null,
            created_at: now(),
            last_login_at: null
        );

        $savedUser = $this->userRepository->create($userEntity);

        return new AuthEntity(
            token: null,
            user: $savedUser,
            message: 'Đăng ký thành công'
        );
    }
}
