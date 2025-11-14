<?php

namespace App\Domain\Auth\Domain\Service;

use App\Domain\Auth\Domain\Entity\UserEntity;

interface OtpServiceInterface
{
    public function saveOtp(UserEntity $user, string $otp, int $expiryMinutes = 5): bool;
    public function findByOtp(string $otp): ?UserEntity;
}
