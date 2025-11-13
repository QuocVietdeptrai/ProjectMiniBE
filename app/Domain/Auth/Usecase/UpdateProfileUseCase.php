<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Helpers\CloudinaryHelper;
use Illuminate\Http\UploadedFile;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateProfileUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}
    public function __invoke(UserEntity $dataEntity, ?UploadedFile $avatar = null): UserEntity
    {
        $user = JWTAuth::user(); 

        $updateData = [];
        if ($dataEntity->name !== $user->name) $updateData['name'] = $dataEntity->name;
        if ($dataEntity->phone !== $user->phone) $updateData['phone'] = $dataEntity->phone;
        if ($dataEntity->address !== $user->address) $updateData['address'] = $dataEntity->address;

        if ($avatar) {
            $updateData['image'] = CloudinaryHelper::upload($avatar, 'avatars');
        }

        if (!empty($updateData)) {
            $dataEntity = $this->userRepository->update($dataEntity, $updateData);
        }

        return $dataEntity;
    }

}
