<?php

namespace App\Domain\Auth\Infrastructure;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class DbUserInfrastructure implements UserRepositoryInterface
{
    //Trả về model User 
    public function findModelByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    //Dựa vào token để tìm UserEntity
    public function findByToken(string $token): ?UserEntity
    {
        $user = JWTAuth::setToken($token)->authenticate();
        return $user ? $this->toEntity($user) : null;
    }

    //Dựa vào model User để tạo token
    public function generateToken(User $userModel): string
    {
        return JWTAuth::fromUser($userModel);
    }


    //Tạo mới user từ UserEntity
    public function create(UserEntity $entity): UserEntity
    {
        $model = User::create($entity->toArray());
        return $this->toEntity($model);
    }

    //Cập nhật mật khẩu user lúc đã đăng nhập
    public function updatePassword(int $id, string $password): bool
    {
        return User::where('id', $id)->update([
            'password' => $password
        ]);
    }


    //Lưu thời gian đăng nhập cuối cùng
    public function saveLastLogin(UserEntity $userEntity): bool
    {
        $model = User::find($userEntity->id); 
        if (!$model) return false;

        $model->last_login_at = Carbon::now();
        return $model->save();
    }



    //Tìm UserEntity theo id
    public function find(int $id): ?UserEntity
    {
        $model = User::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    //Cập nhật thông tin user
    public function update(UserEntity $entity, array $fieldsToUpdate): UserEntity
    {
        $userModel = User::find($entity->id);
        if (!$userModel) {
            throw new \Exception("User not found");
        }

        $userModel->update($fieldsToUpdate);

        return $this->toEntity($userModel->fresh());
    }

    //Cập nhật OTP cho user
    public function updateOtp(string $email, string $otp, int $expiryMinutes = 5): bool
    {
        $user = User::where('email', $email)->first();
        if (!$user) return false;

        $user->otp = $otp;
        $user->	otp_expires_at = now()->addMinutes($expiryMinutes);
        return $user->save();
    }

    //Tìm UserEntity theo OTP
    public function findByOtp(string $otp): ?UserEntity
    {
        $user = User::where('otp', $otp)
                    ->where('otp_expires_at', '>', now())
                    ->first();
        return $user ? $this->toEntity($user) : null;
    }



    //Chuyển từ model User sang UserEntity
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
