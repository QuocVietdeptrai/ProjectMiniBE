<?php

namespace App\Domain\Auth\Infrastructure;

use App\Domain\Auth\Domain\Service\OtpServiceInterface;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Models\User;
use Symfony\Component\Console\Helper\Helper;

class OtpService implements OtpServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    //Lưu OTP cho user với thời gian hết hạn
    public function saveOtp(UserEntity $user, string $otp, int $expiryMinutes = 5): bool
    {
        $userModel = User::find($user->id);
        if (!$userModel) return false;

        $userModel->otp = $otp;
        $userModel->otp_expires_at = now()->addMinutes($expiryMinutes);
        return $userModel->save();
    }

    //Tìm user theo OTP hợp lệ
    public function findByOtp(string $otp): ?UserEntity
    {
        $user = User::where('otp', $otp)
                    ->where('otp_expires_at', '>', now())
                    ->first();
        return $user ? $this->userRepository->toEntity($user) : null;
    }
}
