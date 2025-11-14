<?php 

namespace App\Domain\User\Usecase;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\User\Domain\Repository\UserRepository;
use App\Helpers\CloudinaryHelper;

class CreateUserUseCase
{
    public function __construct(private UserRepository $repo) {}

    public function __invoke(array $data, $imageFile): UserEntity
    {
        $imageUrl = null;
        if($imageFile && $imageFile->isValid()) {
            $imageUrl = CloudinaryHelper::upload($imageFile,'users');
        }

        $entity = new UserEntity(
            id: $data['id'] ?? null,
            name: $data['name'] ?? '',
            email: $data['email'] ?? '',
            role: $data['role'] ?? 'user', // mặc định role là 'user'
            phone: $data['phone'] ?? null,
            address: $data['address'] ?? null,
            image: $imageUrl,
            created_at: $data['created_at'] ?? null,
            last_login_at: $data['last_login_at'] ?? null,
            password: $data['password'] ?? null,
            status: $data['status'] ?? null,
            otp: $data['otp'] ?? null,
            otp_expires_at: $data['otp_expires_at'] ?? null,
        );
        return $this->repo->create($entity);
    }
}