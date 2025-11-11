<?php

namespace App\Domain\Auth\Domain\Repository;

use App\Models\User;
use App\Domain\Auth\Domain\Entity\UserEntity;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function create(array $data): User;
    public function update(User $user, array $data): bool;
    public function saveLastLogin(User $user): bool;
    public function find(int $id): User;
    public function findByOtp(string $otp): ?User;
    public function toEntity(User $user): UserEntity;
}