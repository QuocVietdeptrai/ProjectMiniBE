<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Helpers\MailHelper;
use App\Helpers\RandomHelper;

class ForgotPasswordUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(string $email): array
    {
        $userEntity = $this->userRepository->findByEmail($email);
        if (!$userEntity) {
            return ['success' => false, 'message' => 'Email không tồn tại!'];
        }
        $otp = RandomHelper::generateOTP();

        $updated = $this->userRepository->updateOtp($email, $otp);
        if (!$updated) {
            return ['success' => false, 'message' => 'Không thể cập nhật OTP!'];
        }

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
