<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ResetPasswordUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(string $email, string $password): array
    {
        $user = $this->userRepository->findModelByEmail($email);
        if (!$user) {
            return ['success' => false, 'message' => 'Email không tồn tại!'];
        }

        $this->userRepository->updatePassword($user->id, Hash::make($password));

        return ['success' => true, 'message' => 'Đặt lại mật khẩu thành công!'];
    }
}
