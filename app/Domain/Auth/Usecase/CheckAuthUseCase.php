<?php

namespace App\Domain\Auth\Usecase;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Domain\Auth\Domain\Service\AuthTokenServiceInterface;
use App\Domain\Auth\Exception\NotFoundUserException;
use App\Domain\Auth\Exception\TokenExpired;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class CheckAuthUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private AuthTokenServiceInterface $tokenService
    ) {}

    public function __invoke(string $token): UserEntity
    {
        try {
            // Lấy model User từ token thông qua AuthTokenService
            $userModel = $this->tokenService->userFromToken($token);
            if (!$userModel) {
                throw new NotFoundUserException();
            }

            // Chuyển sang UserEntity
            return $this->userRepository->toEntity($userModel);

        } catch (TokenExpiredException $e) {
            throw new TokenExpiredException();
        } catch (NotFoundUserException $e) {
            throw new NotFoundUserException();
        }
    }
}
