<?php

namespace App\Domain\Auth\Domain\Service;

use App\Models\User;

interface AuthTokenServiceInterface
{
    public function attempt(array $credentials): ?string;
    public function generateToken(User $user): string;
    public function userFromToken(string $token);
    public function user(): ?User;
}
