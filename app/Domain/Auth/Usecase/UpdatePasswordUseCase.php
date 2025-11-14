<?php

namespace App\Domain\Auth\UseCase;

use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;

class UpdatePasswordUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(string $password): array
    {
        $user = JWTAuth::user();
        if (!$user) {
            return ['success' => false, 'message' => 'Người dùng không tồn tại'];
        }

        // Chỉ update password
        $this->userRepository->updatePassword($user->id, Hash::make($password));

        return ['success' => true, 'message' => 'Đổi mật khẩu thành công'];
    }
}
