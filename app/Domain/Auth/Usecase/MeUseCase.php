<?php

namespace App\Domain\Auth\Usecase;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class MeUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(): UserEntity
    {
        $user = JWTAuth::user();
        return $this->userRepository->toEntity($user);
    }
}