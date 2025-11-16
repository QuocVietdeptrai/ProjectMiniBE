<?php 

namespace App\Domain\User\Infrastructure;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\User\Domain\Repository\UserRepository;
use App\Domain\User\Exception\EmailExistsException;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class DbUserInfrastructure implements UserRepository
{
    public function count(): int
    {
        return User::count();
    }
    public function paginate(?string $search , int $perPage = 4): LengthAwarePaginator
    {
        $query = User::query();

        if($search){
            $query->where('name','like',"%{$search}%");
        }
        return $query->orderBy('created_at','desc')->paginate($perPage);
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
            last_login_at: $user->last_login_at,
            password: $user->password,
            status: $user->status,
            otp: $user->otp ?? null,
            otp_expires_at: $user->otp_expires_at
        );
    }

    public function all(?string $search): array
    {
        $query = User::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->orderBy('created_at', 'desc')->get()->map(fn($m) => $this->toEntity($m))->toArray();
    }

    public function create(UserEntity $entity): UserEntity
    {
        if (User::where('email', $entity->email)->exists()) {
            throw new EmailExistsException();
        }
        $model = User::create([
            'name' => $entity->name,
            'email' => $entity->email,
            'phone' => $entity->phone,
            'address' => $entity->address,
            'image' => $entity->image,
            'password' => $entity->password,
            'role' => $entity->role,
            'status' => $entity->status
        ]);
        return $this->toEntity($model);
    }
    public function find(int $id): ?UserEntity
    {
        $model = User::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function update(int $id,UserEntity $entity): ?UserEntity
    {
        $model = User::find($id);
        if(!$model) return null;

        if (User::where('email', $entity->email)->exists()) {
            throw new \Exception("Email '{$entity->email}' đã tồn tại.");
        }

        $model->update([
            'name' => $entity->name,
            'email' => $entity->email,
            'phone' => $entity->phone,
            'address' => $entity->address,
            'image' => $entity->image,
            'password' => $entity->password,
            'role' => $entity->role,
            'status' => $entity->status
        ]);
        return $this->toEntity($model);
    }
    public function delete(int $id): bool
    {
        $model = User::find($id);
        return $model ? $model->delete() : false;
    }
}