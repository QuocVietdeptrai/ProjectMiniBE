<?php 

namespace App\Domain\User\Usecase;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\User\Domain\Repository\UserRepository;
use App\Helpers\CloudinaryHelper;

class UpdateUserUseCase
{
    public function __construct(private UserRepository $repo) {}

    public function __invoke(int $id , array $data , $imageFile = null): ?UserEntity
    {
        $user = $this->repo->find($id);
        $updateData = [];

        if(array_key_exists('name' , $data)) $updateData['name'] = $data['name'];
        if(array_key_exists('email' , $data)) $updateData['email'] = $data['email'];
        if(array_key_exists('phone' , $data)) $updateData['phone'] = $data['phone'];
        if(array_key_exists('address' , $data)) $updateData['address'] = $data['address'];
        if(array_key_exists('password' , $data)) $updateData['password'] = $data['password'];
        if(array_key_exists('role' , $data)) $updateData['role'] = $data['role'];
        if(array_key_exists('status' , $data)) $updateData['status'] = $data['status'];

        if($imageFile && $imageFile->isValid()) {
            $updateData['image'] = CloudinaryHelper::upload($imageFile,'users');
        };

        $entity = new UserEntity(
            id: $id,
            name: $updateData['name'] ?? $user->name,
            email: $updateData['email'] ?? $user->email,
            role: $updateData['role'] ?? $user->role,
            phone: $updateData['phone'] ?? $user->phone,
            address: $updateData['address'] ?? $user->address,
            image: $updateData['image'] ?? $user->image,
            created_at: $user->created_at,
            last_login_at: $user->last_login_at,
            password: $updateData['password'] ?? $user->password,
            status: $updateData['status'] ?? $user->status,
            otp: $updateData['otp'] ?? $user->otp,
            otp_expires_at: $updateData['otp_expires_at'] ?? $user->otp_expires_at,
        );
        return $this->repo->update($id,$entity);
    }
}