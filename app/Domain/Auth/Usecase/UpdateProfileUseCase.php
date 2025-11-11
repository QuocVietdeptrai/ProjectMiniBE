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

    public function __invoke(array $data, ?UploadedFile $avatar = null): UserEntity
    {
        $user = JWTAuth::user();

        $updateData = [];
        if (isset($data['name'])) $updateData['name'] = $data['name'];
        if (isset($data['phone'])) $updateData['phone'] = $data['phone'];
        if (isset($data['address'])) $updateData['address'] = $data['address'];

        if ($avatar) {
            $url = CloudinaryHelper::upload($avatar, 'avatars');
            $updateData['image'] = $url;
        }

        $this->userRepository->update($user, $updateData);

        return $this->userRepository->toEntity($user->fresh());
    }
}