<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Domain\Auth\Exception\AuthenticationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class CheckAuthUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(string $token): UserEntity
    {
        try {
            \Tymon\JWTAuth\Facades\JWTAuth::setToken($token);
            $user = \Tymon\JWTAuth\Facades\JWTAuth::authenticate();

            if (!$user) {
                throw new AuthenticationException('User not found');
            }

            return $this->userRepository->toEntity($user);
        } catch (TokenExpiredException $e) {
            throw new AuthenticationException('Token expired');
        } catch (\Exception $e) {
            throw new AuthenticationException('Unauthorized');
        }
    }
}