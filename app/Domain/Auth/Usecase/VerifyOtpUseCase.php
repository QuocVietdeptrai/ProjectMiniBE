<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;

class VerifyOtpUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(string $otp): array
    {
        $user = $this->userRepository->findByOtp($otp);
        if (!$user) {
            return ['success' => false, 'message' => 'OTP không hợp lệ hoặc đã hết hạn!'];
        }

        return ['success' => true, 'message' => 'Xác nhận OTP thành công!'];
    }
}