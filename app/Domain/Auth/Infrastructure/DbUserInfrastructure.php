<?php

namespace App\Domain\Auth\Infrastructure;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class DbUserInfrastructure implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?UserEntity
    {
        $model = User::where('email', $email)->first();
        return $model ? $this->toEntity($model) : null;
    }

    public function findModelByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByToken(string $token): ?UserEntity
    {
        $user = JWTAuth::setToken($token)->authenticate();
        return $user ? $this->toEntity($user) : null;
    }

    public function generateToken(User $userModel): string
    {
        return JWTAuth::fromUser($userModel);
    }


    public function create(UserEntity $entity): UserEntity
    {
        $model = User::create($entity->toArray());
        return $this->toEntity($model);
    }

    public function updatePassword(int $id, string $password): bool
    {
        return User::where('id', $id)->update([
            'password' => $password
        ]);
    }


    public function saveLastLogin(UserEntity $userEntity): bool
    {
        $model = User::find($userEntity->id); 
        if (!$model) return false;

        $model->last_login_at = Carbon::now();
        return $model->save();
    }




    public function find(int $id): ?UserEntity
    {
        $model = User::find($id);
        return $model ? $this->toEntity($model) : null;
    }
    public function update(UserEntity $entity, array $fieldsToUpdate): UserEntity
    {
        $userModel = User::find($entity->id);
        if (!$userModel) {
            throw new \Exception("User not found");
        }

        $userModel->update($fieldsToUpdate);

        return $this->toEntity($userModel->fresh());
    }

    public function updateOtp(string $email, string $otp, int $expiryMinutes = 5): bool
    {
        $user = User::where('email', $email)->first();
        if (!$user) return false;

        $user->otp = $otp;
        $user->	otp_expires_at = now()->addMinutes($expiryMinutes);
        return $user->save();
    }

    public function findByOtp(string $otp): ?UserEntity
    {
        $user = User::where('otp', $otp)
                    ->where('otp_expires_at', '>', now())
                    ->first();
        return $user ? $this->toEntity($user) : null;
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
            created_at: $user->created_at,
            last_login_at: $user->last_login_at
        );
    }
}
