<?php

namespace App\Domain\Auth\Domain\Repository;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Models\User;

interface UserRepositoryInterface
{
    public function findModelByEmail(string $email): ?User;
    public function create(UserEntity $entity): UserEntity;
    public function update(int $id, UserEntity $entity): ?UserEntity;
    public function updatePassword(int $id, string $password): bool;
    public function saveLastLogin(UserEntity $user): bool;
    public function find(int $id): ?UserEntity;
    public function toEntity(User $user): UserEntity;
}
