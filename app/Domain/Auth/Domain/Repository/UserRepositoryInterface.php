<?php

namespace App\Domain\Auth\Domain\Repository;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Models\User;

interface UserRepositoryInterface
{
    // Trả entity
    public function findByEmail(string $email): ?UserEntity;
    public function findModelByEmail(string $email): ?User;
    public function create(UserEntity $entity): UserEntity;
    public function update(UserEntity $entity, array $fieldsToUpdate): UserEntity;
    public function updatePassword(int $id, string $password): bool;
    public function saveLastLogin(UserEntity $user): bool;
    public function find(int $id): ?UserEntity;
    public function updateOtp(string $email, string $otp, int $expiryMinutes = 5): bool;
    public function findByOtp(string $otp): ?UserEntity;
    public function findByToken(string $token): ?UserEntity;
    public function generateToken(User $userModel): string;
    public function toEntity(User $user): UserEntity;
}
