<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Domain\Auth\Exception\AuthenticationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckAuthUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(string $token): UserEntity
    {
        try {
            $userEntity = $this->userRepository->findByToken($token);
            if (!$userEntity) {
                throw new AuthenticationException('Unauthorized');
            }
            return $userEntity;
        } catch (TokenExpiredException $e) {
            throw new AuthenticationException('Token expired');
        } catch (\Exception $e) {
            throw new AuthenticationException('Unauthorized');
        }
    }
}