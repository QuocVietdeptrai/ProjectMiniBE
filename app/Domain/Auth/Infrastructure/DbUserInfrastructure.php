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
    public function update(int $id, UserEntity $entity): ?UserEntity
    {
        $model = User::find($id);
        if (!$model) return null;

        $model->update([
            'name' => $entity->name,
            'email' => $entity->email,
            'phone' => $entity->phone,
            'address' => $entity->address,
            'image' => $entity->image,
        ]);

        return $this->toEntity($model);
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
