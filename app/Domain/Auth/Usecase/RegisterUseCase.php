<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Domain\Entity\AuthEntity;
use App\Domain\Auth\Domain\Entity\UserEntity;
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
        // Tạo UserEntity từ dữ liệu request
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

        // Lưu vào repository
        $savedUser = $this->userRepository->create($userEntity);

        // Tạo token JWT từ model (repository có thể trả model hoặc entity)
        $token = JWTAuth::fromUser(\App\Models\User::find($savedUser->id));

        return new AuthEntity(
            token: $token,
            message: 'Đăng ký thành công'
        );
    }
}
