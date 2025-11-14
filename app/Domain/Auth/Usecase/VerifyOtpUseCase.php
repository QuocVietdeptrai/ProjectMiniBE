<?php

namespace App\Domain\Auth\Usecase;

use App\Domain\Auth\Domain\Service\OtpServiceInterface;

class VerifyOtpUseCase
{
    public function __construct(
        private OtpServiceInterface $otpService
    ) {}

    public function __invoke(string $otp): array
    {
        $user = $this->otpService->findByOtp($otp);
        if (!$user) {
            return ['success' => false, 'message' => 'OTP không hợp lệ hoặc đã hết hạn!'];
        }

        return ['success' => true, 'message' => 'Xác nhận OTP thành công!'];
    }
}