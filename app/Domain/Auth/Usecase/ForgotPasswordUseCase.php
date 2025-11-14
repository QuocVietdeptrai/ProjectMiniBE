<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Domain\Auth\Domain\Service\OtpServiceInterface;
use App\Helpers\MailHelper;
use App\Helpers\RandomHelper;

class ForgotPasswordUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private OtpServiceInterface $otpService
    ) {}

    public function __invoke(string $email): array
    {
        // Lấy user từ repository
        $userModel = $this->userRepository->findModelByEmail($email);
        if (!$userModel) {
            return ['success' => false, 'message' => 'Email không tồn tại!'];
        }

        $otp = RandomHelper::generateOTP();
        $userEntity = $this->userRepository->toEntity($userModel);
        // Sinh OTP và lưu vào DB
        $saved = $this->otpService->saveOtp($userEntity, $otp);

        $subject = "Mã OTP khôi phục mật khẩu";
        $content = "<p>Xin chào <b>{$userEntity->name}</b>,</p>
                    <p>Mã OTP của bạn là: <b>{$otp}</b></p>
                    <p>OTP có hiệu lực trong 5 phút.</p>";

        $sent = MailHelper::sendMail($userEntity->email, $subject, $content);

        return [
            'success' => $sent,
            'message' => $sent ? 'Đã gửi OTP tới email!' : 'Gửi mail thất bại!'
        ];
    }
}
