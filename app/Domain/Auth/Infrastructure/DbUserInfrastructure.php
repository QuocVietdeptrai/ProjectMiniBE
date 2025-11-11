<?php

namespace App\Domain\Auth\Infrastructure;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;

class DbUserInfrastructure implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function saveLastLogin(User $user): bool
    {
        $user->last_login_at = Carbon::now();
        return $user->save();
    }

    public function find(int $id): User
    {
        return User::findOrFail($id);
    }

    public function findByOtp(string $otp): ?User
    {
        return User::where('otp', $otp)->first();
    }

    public function toEntity(User $user): UserEntity
    {
        return new UserEntity(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            role: $user->role,
            phone: $user->phone,
            address: $user->address,
            image: $user->image,
            created_at: $user->created_at->format('Y-m-d H:i:s')
        );
    }
}